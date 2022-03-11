<?php

namespace App\Http\Controllers\Auth;

use Socialite;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    //Github login
    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }
    //Github callback
    public function handleGithubCallback()
    {
        $user = Socialite::driver('github')->user();
 
        $this->_registerorLoginUser($user);
        //redirect back to home
        return redirect()->route('dashboard');
    }


    //Facebook login
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }
    //Facebook callback
    public function handleFacebookCallback()
    {
        $user = Socialite::driver('facebook')->user();
 
        $this->_registerorLoginUser($user);
        //redirect back to dashboard
        return redirect()->route('dashboard');
    }

    //google login
    public function redirectToGoogle()
    {
        // dd('hii');
        return Socialite::driver('google')->redirect();
    }
    //google callback
    public function handleGoogleCallback()
    {
        // dd('hii');
        $user = Socialite::driver('google')->user();
// //  dd($user);
//         $user = User::where('email', '=', $googleuser->email)->first();
//         if(!$user){ 
//             $user = User::create([
//             "name" => $googleuser->name,
//             "email" => $googleuser->email,
//             "provider_id" => $googleuser->provider_id,
//             "avatar" => $googleuser->avatar,

//             ]);
//         }

//         Auth::login($user);
        $this->_registerorLoginUser($user);
        //redirect back to dashboard
        return redirect()->route('dashboard');
                //redirect back to dashboard
        // return redirect('/dashboard');
    }

    protected function _registerorLoginUser($data){
        // dd("hii");
        $user = User::where('email', '=', $data->email)->first();
        // dd($data);
        if(!$user){
            $user = new User();
            $user->name = $data->name;
            $user->email = $data->email;
            $user->provider_id = $data->id;
            $user->avatar = $data->avatar;
            $user->save();

        }

        Auth::login($user);
    }

}
