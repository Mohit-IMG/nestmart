<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Socialite;
use App\Models\User;
use Illuminate\Foundation\Auth\RedirectsUsers;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Session;
use Str;

class SocialLoginController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        // echo "hii";die;
        try {
            $user = Socialite::driver('google')->user();
    
            // Check if the user already exists in your database based on the email
            $existingUser = User::where('email', $user->email)->first();
    
            if ($existingUser) {
                // dd($existingUser);
                // Log in the existing user
                Auth::login($existingUser);
            } else {
                // Create a new user record
                $newUser = new User();
                $newUser->email = $user->email;
                $newUser->name = Str::ucfirst($user->name);
                $newUser->email = $user->email; // Assuming $user->email is already defined somewhere
                $newUser->referrer_id = 'SK97579';
                $newUser->user_type = 'Customer';
                $newUser->designation_id = 2;
                $newUser->unique_id = \App\Helpers\commonHelper::generateUniqueID($newUser->name);
                $newUser->save();
                Auth::login($newUser);
            }
    
            return $this->redirectTo(); // Use the dynamic redirection
        } catch (\Exception $e) {
            // dd($e->getMessage());
            Session::flash('5fernsadminerror', 'Google login failed');
            return redirect('login');
        }
    }

    protected function redirectTo()
    {
        if (\Auth::check()) {
            if (\Auth::user()->user_type == 'Admin') {
                return redirect('/admin/dashboard');
            } else {
                return redirect('my-account');
            }
        }
    
        // If authentication fails for some reason, redirect to the login page
        return redirect('/login');
    }
        
    
    
    
    
    
}


