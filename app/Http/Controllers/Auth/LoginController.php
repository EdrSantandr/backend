<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use App\User;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        //
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        //
    }


    public function redirectToProviderFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallbackFacebook()
    {
        $user = Socialite::driver('facebook')->user();
        if ($user){
            return $this->manageUser($user,'facebook');
        }else{
            return redirect('/');
        }
    }

    public function redirectToProviderGithub()
    {
        return Socialite::driver('github')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallbackGithub()
    {
        $user = Socialite::driver('github')->user();
        if ($user){
            return $this->manageUser($user,'github');
        }else{
            return redirect('/');
        }
    }
    //https://backend.test/login/github
    public function manageUser($userSocial,$provider){
        
        $users=User::where(['email' => $userSocial->email])->first();
        //dd($users['id']) =1310323;
        if ($users){
            Auth::login($users);
            return redirect('/');
        }else{
            //dd($userSocial['name']);
            $name=is_null($userSocial->name)?"":$userSocial->name;
            $user = User::create([
                'name'          => $name,
                'email'         => $userSocial->email,
                'image'         => $userSocial->avatar,
                'provider_id'   => $userSocial->id,
                'provider'      => $provider,
            ]);
            Auth::login($user);
            return redirect(RouteServiceProvider::HOME);
        }
    }
}
