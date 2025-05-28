<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Company\CompanyRegesterController;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    protected $companyRegesterController;

    public function __construct(CompanyRegesterController $companyRegesterController)
    {
        $this->companyRegesterController = $companyRegesterController;
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            
            // Check if user exists
            $user = User::on('mysql_general')->where('email', $googleUser->email)->first();
            // $googleUser->getId();          // Google ID
                // $googleUser->getName();        // Full name
                // $googleUser->getEmail();       // Email address
                // $googleUser->getAvatar();      // Profile picture URL
                // return $googleUser->token;
                // return $googleUser->avatar;
                // return $googleUser->name;
                // return $googleUser->email;
            if (!$user) {
                // Create a new request with the required fields
                $request = new Request([
                    'company_name' => $googleUser->name ,
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'password' => Hash::make(Str::random(16)),
                    'password_confirmation' => Hash::make(Str::random(16)),
                    'subscription_plan_id' => 1, // Assuming 1 is the free plan ID
                    'company_logo' => $googleUser->avatar
                ]);

                // Register the user using CompanyRegesterController
                $response = $this->companyRegesterController->register($request);
                \Log::info($response);

                if ($response instanceof \Illuminate\Http\RedirectResponse) {
                    // If registration was successful, update the user's google_id
                    $user = User::on('mysql_general')->where('email', $googleUser->email)->first();
                    $user->google_id = $googleUser->id;
                    $user->save();
                    
                    // return $response;
                }
                
                // return redirect()->route('login')->with('error', 'Registration failed');
            }

            // Update google_id if not set
            if (!$user->google_id) {
                $user->google_id = $googleUser->id;
                $user->save();
            }

            // Login the user
            Auth::login($user);

            return redirect()->route('company.dashboard');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Google authentication failed: ' . $e->getMessage());
        }
    }
} 