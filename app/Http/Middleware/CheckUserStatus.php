<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Company\User;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('login') || 
        $request->is('register') || 
        $request->is('logout') ||
        $request->is('password/*') ||
        $request->is('subscription/payments*') ||
        $request->is('subscription/payment/*')) {
        return $next($request);
    }
        $user = Auth::user();

        \Log::info('companylUser............: '.$user);
        // Get user data from general database
        $generalUser = User::on('mysql_general')->where('email', $user->email)->first();
        \Log::info('generalUser............: '.$generalUser);
        if (!$generalUser) {
            Auth::logout();
            \Log::info('generalUser............: !$generalUser');
            return redirect()->route('login')->with('error', 'User not found in general database.');
        }

        // Get the admin user who created this user
        $adminUser = User::on('mysql_general')
            ->where('db_name', $generalUser->db_name)
            ->where('role_name', 'Admin')
            ->first();

        if (!$adminUser) {
            Auth::logout();
            session()->flash('error', 'Invalid user access. No associated admin found.');
            return redirect()->back()->with('error', 'Invalid user access. No associated admin found.');
        }

        // Check if admin's company is active
        if (!$adminUser || !$adminUser->is_active) {
            Auth::logout();
            session()->flash('error', 'The company associated with your account is not active.');
            return redirect()->back()->with('error', 'The company associated with your account is not active.');
        }

        // Check subscription status of admin's company
        if (!$adminUser->activeSubscription) {
            \Log::info('Admin company subscription............: expired');

            if($generalUser->is_admin){
                session()->flash('error', 'Your company\'s subscription has expired..');
                return redirect()->route('subscription.renew');
            }else{
                session()->flash('error', 'Your company\'s subscription has expired.');
    
                return redirect()->route('subscription.renew')->with('Your company\'s subscription has expired.');
               }
        }

        // Check payment status of admin's company
        // Check if user is on free plan
        if ($adminUser->activeSubscription && $adminUser->activeSubscription->subscriptionPlan->is_free) {
            return $next($request);
        }

        // For paid plans, check payment status
        $latestCompanyPayment = $adminUser->payments()->latest()->first();
        if (!$latestCompanyPayment || $latestCompanyPayment->isPending() || $latestCompanyPayment->isFailed()) {
            \Log::info('Company payment status: ' . ($latestCompanyPayment ? $latestCompanyPayment->status : 'no payment found'));
            if($generalUser->is_admin){
                return redirect()->route('subscription.payments');
            }else{
                session()->flash('error', 'Your company\'s payment is incomplete or failed.');
                return redirect()->route('subscription.payments')->with('error', 'Your company\'s payment is incomplete or failed. ');
            }
        }

        // Check if user is active
        if ($generalUser->is_active !== true) {
            \Log::info('generalUser............: status === !active');
            Auth::logout();
            session()->flash('error', 'Your account is not active. Please contact support.');
            return redirect()->back()->with('error', 'Your account is not active. Please contact support.');
        }
        \Log::info('database name............: '. $generalUser->db_name);

        // Update local user data with general database data
        // $generalUser_supscription=  $generalUser->subscription;
        // \Log::info('generalUser............: $generalUser->subscription'. $generalUser->subscription->subscriptionPlan);

        // $user->subscription_plan = $generalUser_supscription->subscriptionPlan;
        // $user->subscription_expires_at =  $generalUser_supscription->ends_at;
        // $user->subscription_status = $generalUser->subscription_status;
        // $user->payment_status = $generalUser->payment_status;
        // $user->save();

        return $next($request);
    }
} 