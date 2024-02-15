<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User; 
use Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetMail;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;


    public function updatePassword(Request $request)
    {
        if ($request->ajax()) {
            // dd($request);
            try {
                $newPassword = $request->npassword;
    
                // Validate the new password
                $rules = [
                    'npassword' => 'required',
                ];
    
                $validator = Validator::make($request->all(), $rules);
    
                if ($validator->fails()) {
                    $errors = $validator->messages()->first();
                    return response()->json(['msg' => $errors], 400);
                }
    
                $user = User::find($request->user()->id);
    
                if (!$user) {
                    return response()->json(['msg' => 'User not found.'], 404);
                }
    
                // Check if the provided current password matches the user's actual password
                $currentPassword = $request->password;
                // dd($currentPassword);
                $currentPasswordMatches = Hash::check($currentPassword, $user->password);
    
                if (!$currentPasswordMatches) {
                    return response()->json(['msg' => 'Current password is incorrect.', 'success' => false], 200);
                }
    
                // Update the user's password
                $user->password = Hash::make($newPassword);
                $user->save();
    
                return response()->json(['msg' => 'Password updated successfully.', 'success' => true], 200);
    
            } catch (\Exception $e) {
                return response()->json(['msg' => 'Something went wrong.', 'success' => false], 500);
            }
        }
    }
    


    public function checkCurrentPassword(Request $request) {
        if ($request->ajax()) {
            try {
                $cPassword = $request->current_password;
    
                if (empty($cPassword)) {
                    return response()->json(['msg' => 'Current Password Cannot Be Empty.'], 400);
                }
    
                $user = User::find($request->user()->id);
    
                if (!$user) {
                    return response()->json(['msg' => 'User not found.'], 404);
                }
    
                $passwordMatches = Hash::check($cPassword, $user->password);
    
                if ($passwordMatches) {
                    return response()->json(['msg' => 'You Can Reset Password.', 'success' => 'success'], 200);
                } else {
                    return response()->json(['msg' => 'Password Not Matching. Try Again.', 'success' => 'err'], 200);
                }
    
            } catch (\Exception $e) {
                return response()->json(['msg' => 'Something Went Wrong.'], 500);
            }
        }
    }





    //---------------------- forgot-password----------------------//


    public function forgortPasswordPage(){

        return view('auth.passwords.forgotPassword');
    }


    public function sendOTPToEmail($email, $otp)
    {
        $emailData = [
            'email' => $email,
            'subject' => 'Password Reset OTP',
            'template' => 'otp',
            'otp' => $otp,
        ];
    
        \App\Helpers\commonHelper::emailSendToUser($emailData);
    }
    



    public function sendOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['message' => 'Invalid email or user not found.'], 400);
        }
    
        $otp = \App\Helpers\commonHelper::getOtp();
    
        $data = [
            'email' => $request->input('email'), 
            'otp' => $otp,
            'image' => 'https://i.ibb.co/PrrYkH1/logo.png',
            'template' => 'otp',
            'subject' => 'Password reset email from Nest Mart Grocery',
        ];

        \App\Helpers\commonHelper::emailSendToUser($data);

        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->input('email')],
            [
                'token' => $otp,
                'created_at' => now(),
            ]
        );
    
        return response()->json(['message' => 'OTP sent successfully.']);
    }
    
    

public function resendOTP(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email|exists:users,email',
    ]);

    if ($validator->fails()) {
        return response()->json(['message' => 'Invalid email or user not found.'], 400);
    }

    $otp =  \App\Helpers\commonHelper::getOtp(); 

    $data = [
        'otp' => $otp,
        'image' => 'https://i.ibb.co/PrrYkH1/logo.png',
        'template' => 'otp',
        'subject' => 'Welcome Email from Nest Mart Grocery',
    ];

    DB::table('password_resets')
        ->where('email', $request->input('email'))
        ->update([
            'token' => $otp,
            'created_at' => now(),
        ]);

    return response()->json(['message' => 'OTP resent successfully.']);
}

public function resetPassword(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email|exists:users,email',
        'otp' => 'required',
        'new_password' => 'required|min:8',
    ]);

    if ($validator->fails()) {
        return response()->json(['message' => 'Invalid data provided.', 'errors' => $validator->errors()], 400);
    }

    $email = $request->input('email');
    $otp = $request->input('otp');
    $newPassword = $request->input('new_password');

    $otpMatch = DB::table('password_resets')
        ->where('email', $email)
        ->where('token', $otp)
        ->where('created_at', '>', now()->subMinutes(30)) // OTP should be valid for 30 minutes
        ->exists();

    if (!$otpMatch) {
        return response()->json(['message' => 'Invalid OTP.'], 400);
    }

    $user = User::where('email', $email)->first();

    if (!$user) {
        return response()->json(['message' => 'User not found.'], 404);
    }

    $user->password = Hash::make($newPassword);
    $user->save();

    DB::table('password_resets')
        ->where('email', $email)
        ->delete();

        return redirect()->route('user-login')->with('passwordRested', 'Password reset successful. You may now log in.');
    }

}
