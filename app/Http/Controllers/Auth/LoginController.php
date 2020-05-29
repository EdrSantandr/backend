<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use App\User;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use \Illuminate\Http\Request;

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
    
    public function redirectTo()
    {
        if (auth()->user()->type === 'admin') {
            return '/home';
        }
        if (auth()->user()->type !== 'admin') {
            return '/eder';
        }
        return '/';
    }

    public function redirectPath()
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }
        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
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
        //dd($userSocial);
        if ($users){
            Auth::login($users);
            return redirect('/');
        }else{
            $base64FileName=$this->save_base64_encode_image($userSocial->avatar);
            $name=is_null($userSocial->name)?"":$userSocial->name;
            $user = User::create([
                'name'          => $name,
                'email'         => $userSocial->email,
                'image'         => $userSocial->avatar,
                'photo'         => $base64FileName,
                'provider_id'   => $userSocial->id,
                'provider'      => $provider,
            ]);
            Auth::login($user);
            return redirect(RouteServiceProvider::HOME);
        }
        
    }
    /**Convert from base64 to save file and return filename**/
    public function save_base64_encode_image ($url=string) {
        $mime=$this->get_image_mime_type($url); //Obtener el mime de la imagen url
        $imageData=base64_encode(file_get_contents($url)); //Encodear el archivo en base4
        $imageBase64Mime = 'data:'.$mime.';base64,'.$imageData; //formar el base64 con encabezados
        //dd($imageBase64Mime);
        $name= time().'.'.explode('/',explode(':',substr($imageBase64Mime,0, strpos($imageBase64Mime,';')))[1])[1];
        \Image::make($imageBase64Mime)->save(public_path('img/profile/').$name);
        return $name;
    }
    /** Get the mime from url*/
    public function get_image_mime_type($image_path)
    {
        $mimes  = array(
            IMAGETYPE_GIF => "image/gif",
            IMAGETYPE_JPEG => "image/jpg",
            IMAGETYPE_PNG => "image/png",
            IMAGETYPE_SWF => "image/swf",
            IMAGETYPE_PSD => "image/psd",
            IMAGETYPE_BMP => "image/bmp",
            IMAGETYPE_TIFF_II => "image/tiff",
            IMAGETYPE_TIFF_MM => "image/tiff",
            IMAGETYPE_JPC => "image/jpc",
            IMAGETYPE_JP2 => "image/jp2",
            IMAGETYPE_JPX => "image/jpx",
            IMAGETYPE_JB2 => "image/jb2",
            IMAGETYPE_SWC => "image/swc",
            IMAGETYPE_IFF => "image/iff",
            IMAGETYPE_WBMP => "image/wbmp",
            IMAGETYPE_XBM => "image/xbm",
            IMAGETYPE_ICO => "image/ico");

        if (($image_type = exif_imagetype($image_path))
            && (array_key_exists($image_type ,$mimes)))
        {
            return $mimes[$image_type];
        }
        else
        {
            return FALSE;
        }
    }

}
