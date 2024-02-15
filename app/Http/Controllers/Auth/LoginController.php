<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Helpers\commonHelper;
use App\Mail\MagicLinkEmail; // Import the MagicLinkEmail Mailable
use Illuminate\Support\Facades\Mail;
use Str;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->redirectTo = '/admin/dashboard'; // set the default redirect path

    }
	

    public function login(Request $request)
    {
        // dd($request);
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        $credentials = $request->only('email', 'password');
    
        if (Auth::attempt($credentials, $request->has('remember'))) {
            \App\Helpers\commonHelper::updateMenu(\Auth::user()->designation_id);
    
            if (\Auth::user()->user_type == 'Admin') {
                return redirect('/admin/dashboard');
            } else {
                return redirect('/');
            }
        }
    
        \Session::flash('5fernsadminerror', "Oops! You have entered invalid credentials");
        return redirect("login")->withInput($request->only('email', 'remember'));
    }
    
	public function logout() {

        Session::forget('fivefernsadminrexceptionurl');
        Session::forget('fivefernsadminmenu');
        Session::forget('userToken');

        if(\Auth::user()->designation_id == '2'){
            Auth::logout();
            return redirect('/user-login');
        }else{
            Auth::logout();
            return redirect('/login');
        }
        
    }


    public function userLoginForm(Request $request){
        return view('auth.user-login');
    }

    public function userLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        $credentials = $request->only('email', 'password');
    
        if (Auth::attempt($credentials, $request->has('remember'))) {
            \App\Helpers\commonHelper::updateMenu(\Auth::user()->designation_id);
    
            $token = \Auth::user()->createToken('authToken')->accessToken;
            Session::put('userToken', $token);
            return redirect('/');
            
        }
    
        \Session::flash('5fernsadminerror', "Oops! You have entered invalid credentials");
        return redirect("user-login")->withInput($request->only('email', 'remember'));
    }
    
    public function generateAndSendMagicLink(Request $request){
        $request->validate([
            'email' => 'required|email',
        ]);
    
        $user = \App\Models\User::where('email', $request->email)->first();
    
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
    
        $token = Str::random(60);
        $user->magic_link_token = $token;
        $user->save();
    
        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'image' => 'https://i.ibb.co/PrrYkH1/logo.png',
            'subject' => 'Magic Link Login',
            'template' => 'magic-link',
            'magicLink' => $token,
        ];
    
        \App\Helpers\commonHelper::emailSendToUser($data);
    
        return response()->json(['message' => 'Magic link sent to your email. Please check your inbox']);
    }
    
    public function verifyMagicLink($token) {
        $user = \App\Models\User::where('magic_link_token', $token)->first();
    
        if (!$user) {
            return response()->view('errors.magic_link_invalid', [], 404);
        }
    
        auth()->login($user);
    
        // Set the userToken session
        $token = $user->createToken('magicLinkToken')->accessToken;
        Session::put('userToken', $token);
    
        return redirect('/user/dashboard')->with('accessToken', $token);
    }
    
    
    


}
