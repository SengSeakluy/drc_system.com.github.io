<?php

namespace App\Http\Controllers\Auth;

use App\Models\Frontend\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = "/profile";

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $message=[
            'regex' => 'Password must contain at least 8 characters, one uppercase letter and one number. Usage of special characters are limited to the following @$=!:.#%*&()'
        ];

        return Validator::make($data, [
            'email' => ['required', 'email', 'max:255', 'unique:customers'],
            'country' => ['required'],
            'contact_number' => ['required', 'string'],
            'password' => [
                'required',
                'min:8',
                'regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            ],
            'confirm_password' => ['required', 'same:password'],
        ],$message);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\Frontend\Register;
     */
    protected function create(array $data)
    {
        return Customer::create([
            'name' => $data['email'],
            'email' => $data['email'],
            'country' => $data['country'],
            'contact_number' => $data['contact_number'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
