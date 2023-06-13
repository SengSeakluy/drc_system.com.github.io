<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\CustomClasses\SendOTP;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\CustomClasses\ChangeAppLanguage;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class CustomVoyagerAuthController extends Controller
{
    use AuthenticatesUsers;

    public function login()
    {

        if ($this->guard()->user()) {
            return redirect()->route('voyager.dashboard');
        }

        $slug = 'login';

        return Voyager::view('voyager::login', compact('slug'));
    }

    public function sigin(Request $request, $locale)
    {

        if ($this->guard()->user()) {
            return redirect()->route('voyager.dashboard');
        }

        // Apply App Change Language
        $slug = 'signin';
        ChangeAppLanguage::setLocale($locale);
        // end language section

        return Voyager::view('voyager::login', compact('slug'));
    }

    public function logout()
    {
        Auth::logout();
        $local = Session::get('local') ?? 'en';
        return redirect('/admin/signin/'.$local);
    }

    public function postLogin(Request $request)
    {

        $this->validateLogin($request);
        // perform OTP in production
        if (app()->environment() == 'production') {
            // If the class is using the ThrottlesLogins trait, we can automatically throttle
            // the login attempts for this application. We'll key this by the username and
            // the IP address of the client making these requests into this application.
            if ($this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);
                
                return $this->sendLockoutResponse($request);
            }
            
            $user = User::where('email',$request->email)->first();
            if($user !== null){
                $password = Hash::check($request->password,$user->password);
                if($password){
                    $data =['id'=>$user->id,'email'=>$user->email];
                    SendOTP::SendOTP($data);
                    Session::put('credentials',['email'=>$request->email,'password'=>$request->password,'user'=>$user->id]);
                    return redirect('/admin/verify');
                }
            }

            // If the login attempt was unsuccessful we will increment the number of attempts
            // to login and redirect the user back to the login form. Of course, when this
            // user surpasses their maximum number of attempts they will get locked out.
            $this->incrementLoginAttempts($request);

            return $this->sendFailedLoginResponse($request);
        }

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->credentials($request);

        if ($this->guard()->attempt($credentials, $request->has('remember'))) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /*
     * Preempts $redirectTo member variable (from RedirectsUsers trait)
     */
    public function redirectTo()
    {
        return config('voyager.user.redirect', route('voyager.dashboard'));
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard(app('VoyagerGuard'));
    }
}
