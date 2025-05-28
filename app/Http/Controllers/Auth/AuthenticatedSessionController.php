<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Http\Controllers\LogController;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {

        $request->authenticate();

        $request->session()->regenerate();
         $log = new LogController();
        $log->newLog(Auth::user()->id,"تسجيل دخول علي النظام");
        
        // $log->newLog(Auth::user()->id,"تسجيل دخول علي النظام <br/> ".$request->header('User-Agent'));
        if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('super admin')) {
            return redirect()->intended('/home');
        } else if (Auth::user()->hasRole('stuff')) {
            return redirect()->intended('/stores');
        } else if (Auth::user()->hasRole('accountant')) {
            return redirect()->intended('/qayd/searchaccount');
        } else if (Auth::user()->hasRole('operation')) {
            return redirect()->intended('/parts');
        } else {
            return redirect()->intended(auth()->user()->getRedirectRoute());
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
         $log = new LogController();
        $log->newLog(Auth::user()->id,"تسجيل خروج علي النظام");
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
