<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect()->route('company.dashboard')
                ->with('error', 'غير مصرح لك بالوصول إلى لوحة التحكم');
        }

        return $next($request);
    }
}
