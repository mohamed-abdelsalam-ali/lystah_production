<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Company\User;

class CheckSuperAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Get user from general database
        $user_general = User::on('mysql_general')->where('email', $user->email)->first();
        // \Log::info('user_general: '.$user_general);
        // Check if user has super_admin role in general database
        if (!$user_general || !$user_general->role_name || $user_general->role_name !== 'super_admin') {
            abort(403, 'Unauthorized action. Only super admin can access this page.');
        }

        return $next($request);
    }
} 