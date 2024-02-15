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
use Illuminate\Support\Facades\Storage;


class SocialLoginController extends Controller
{
    public function redirectToGitHub()
    {
        // echo "hii";die;
        return Socialite::driver('github')->redirect();
    }

    // public function handleGitHubCallback()
    // {
    //     // echo "hisdddi";die;

    //     try {


    //    // GitHub user call
    //    $user = Socialite::driver('github')->user();

    //    // Log after GitHub user call
    //    echo 'After GitHub User Call: ' . json_encode($user);die;

    //         // Check if the user already exists in your database based on the email
    //         $existingUser = User::where('email', $user->email)->first();

    //         if ($existingUser) {
    //             // Log in the existing user
    //             Auth::login($existingUser);
    //         } else {
    //             // Check if the user has an account with a password
    //             $userWithPassword = User::where('email', $user->email)->whereNotNull('password')->first();

    //             if ($userWithPassword) {
    //                 // User has an account with a password, redirect to login with password
    //                 return redirect()->route('login')->with('email', $user->email);
    //             }

    //             // Create a new user record
    //             $newUser = new User();
    //             $newUser->name = Str::ucfirst($user->nickname);
    //             $newUser->email = $user->email;
    //             $newUser->referrer_id = 'SK97579';
    //             $newUser->user_type = 'Customer';
    //             $newUser->coupencode = 'welcome';
    //             $newUser->github_id = $user->id;
    //             $newUser->designation_id = 2;
    //             $newUser->unique_id = \App\Helpers\commonHelper::generateUniqueID($newUser->name);
    //             $newUser->save();

    //             // Save the github_id after saving the user
    //             $newUser->github_id = $newUser->id;
    //             $newUser->save();
    //             Auth::login($newUser);
    //         }
    //         $token = \Auth::user()->createToken('authToken')->accessToken;
    //         Session::put('userToken', $token);
    //         return $this->redirectTo(); // Use the dynamic redirection
    //     } catch (\Exception $e) {
    //         Session::flash('5fernsadminerror', 'GitHub login failed');
    //         return redirect('login');
    //     }
    // }
    public function handleGitHubCallback()
    {
        try {

            $user = Socialite::driver('github')->user();
            $existingUser = User::where('email', $user->email)->first();
    
            if ($existingUser) {
                Auth::login($existingUser);
            } else {
                $userWithPassword = User::where('email', $user->email)->whereNotNull('password')->first();
    
                if ($userWithPassword) {
                    return redirect()->route('login')->with('email', $user->email);
                }
    
                $newUser = new User();
                $newUser->name = Str::ucfirst($user->nickname);
                $newUser->email = $user->email;
                $newUser->referrer_id = 'SK97579';
                $newUser->user_type = 'Customer';
                $newUser->coupencode = 'welcome';
                $contents = file_get_contents($user->avatar);
                $filename = strtotime(now()) . rand(11, 99) . '.jpg';
                Storage::put("public/uploads/profile_images/{$filename}", $contents);
                $newUser->profileimage = $filename; 
                $newUser->designation_id = 2;
                $newUser->unique_id = \App\Helpers\commonHelper::generateUniqueID($newUser->name);
                $newUser->save();
                // Save the github_id after saving the user
                $newUser->github_id = $newUser->id;
                $newUser->save();

                Auth::login($newUser);
            }
    
            $token = \Auth::user()->createToken('authToken')->accessToken;
            Session::put('userToken', $token);
    
            // Use the dynamic redirection
            return $this->redirectTo();
        } catch (\Exception $e) {
            // Log the exception
            \Illuminate\Support\Facades\Log::error('GitHub login failed: ' . $e->getMessage());
    
            // Flash an error message for display
            Session::flash('5fernsadminerror', 'GitHub login failed');
    
            // Redirect to the login page
            return redirect('login');
        }
    }
    

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            
            $user = Socialite::driver('google')->user();
    
            // Check if the user already exists in your database based on the email
            $existingUser = User::where('email', $user->email)->first();
    
            if ($existingUser) {
                // Log in the existing user
                Auth::login($existingUser);
            } else {
                // Check if the user has an account with a password
                $userWithPassword = User::where('email', $user->email)->whereNotNull('password')->first();
    
                if ($userWithPassword) {
                    // User has an account with a password, redirect to login with password
                    return redirect()->route('login')->with('email', $user->email);
                }
    
                // Create a new user record
                $newUser = new User();
                $newUser->name = Str::ucfirst($user->name);
                $newUser->email = $user->email;
                $newUser->referrer_id = 'SK97579';
                $newUser->user_type = 'Customer';
                $newUser->coupencode = 'welcome';
                $newUser->google_id = $newUser->id;
                $newUser->designation_id = 2;
                $newUser->unique_id = \App\Helpers\commonHelper::generateUniqueID($newUser->name);
                $newUser->save();

                // Save the google_id after saving the user
                $newUser->google_id = $newUser->id;
                $newUser->save();
                Auth::login($newUser);
            }
            $token = \Auth::user()->createToken('authToken')->accessToken;
            Session::put('userToken', $token);
            return $this->redirectTo(); // Use the dynamic redirection
        } catch (\Exception $e) {
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
                return redirect()->route('dashboard');
            }
        }
    
        // If authentication fails for some reason, redirect to the login page
        return redirect('/login');
    }
        
    
    
    
            
    
}


