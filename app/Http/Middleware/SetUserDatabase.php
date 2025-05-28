<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class SetUserDatabase
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
        
            
            // Get the current database from session or user model
            $currentDb = session('current_database') ?? $user->db_name;
            // \Log::info('currentDb: ' . Config::get('database.connections.mysql.database'));
            // If we're not using the correct database, switch to it
            if ($currentDb && Config::get('database.connections.mysql.database') !== $currentDb) {
                try {
                    // Store original database if not already stored
                    if (!session()->has('original_database')) {
                        session(['original_database' => Config::get('database.connections.mysql.database')]);
                    }
                    
                    // Switch to company database
                    Config::set('database.connections.mysql.database', $currentDb);
                    DB::purge('mysql');
                    DB::reconnect('mysql');
                    
                    // Verify connection and select database
                    $pdo = DB::connection()->getPdo();
                    $pdo->exec("USE `{$currentDb}`");
                    
                    // First get the basic user info
                    $companyUser = User::where('email', $user->email)->first();
                    // \Log::info('companyUser: ' . $companyUser);
                    if ($companyUser) {
                        // Now load the roles separately to avoid any potential issues
                        $roleNames = DB::table('model_has_roles')
                            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                            ->where('model_has_roles.model_id', $companyUser->id)
                            ->where('model_has_roles.model_type', 'App\\Models\\User')
                            ->select('roles.name')
                            ->get()
                            ->map(function($item) {
                                return $item->name;
                            })
                            ->toArray();
                        
                        // Assign roles to the user
                        $companyUser->setRelation('roles', collect($roleNames));
                        
                        // Set the authenticated user
                        Auth::setUser($companyUser);
                    }
                    
                    // Log the database switch
                    // \Log::info("Switched to company database: {$currentDb}");
                } catch (\Exception $e) {
                    // Log the error
                    \Log::error('Database switching failed: ' . $e->getMessage());
                    
                    // Fallback to original database if available
                    if (session()->has('original_database')) {
                        Config::set('database.connections.mysql.database', session('original_database'));
                        DB::purge('mysql');
                        DB::reconnect('mysql');
                    }
                    
                    // Logout user if database connection fails
                    Auth::logout();
                    return redirect()->route('login')->with('error', 'Database connection failed. Please try again later.');
                }
            }
        }
        // \Log::info('request Db: ' . $request->session()->get('current_database'));
        
        return $next($request);
    }
}
