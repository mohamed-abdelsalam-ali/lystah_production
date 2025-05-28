<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetDatabaseConnection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         // Only proceed if user is authenticated
         if (Auth::check()) {
            // Get the current authenticated user
            $user = Auth::user();
            
            // Check if user has a database_name attribute (adjust as needed)
            if (isset($user->db_name) && !empty($user->db_name)) {
                // Set the database configuration dynamically
                Config::set('database.connections.mysql.database', $user->db_name);
                
                // Optional: You can set other connection parameters if needed
                // Config::set('database.connections.mysql.username', 'custom_user');
                // Config::set('database.connections.mysql.password', 'custom_password');
                
                // Purge and reconnect to ensure new settings take effect
                DB::purge('mysql');
                DB::reconnect('mysql');
            }
            // try {
            //     DB::connection()->getPdo();
            //     return true;
            // } catch (\Exception $e) {
            //     return false;
            // }
            // Alternative: If you're storing database name in session
            // if ($request->session()->has('current_database')) {
            //     Config::set('database.connections.mysql.database', 
            //         $request->session()->get('current_database'));
            //     DB::purge('mysql');
            //     DB::reconnect('mysql');
            // }
        }

        return $next($request);
    
    }
}
