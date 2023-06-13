<?php

namespace App\Http\Controllers\Admin;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\CustomClasses\ChangeAppLanguage;


class CustomerController extends Controller
{
    // Show Register/Create Form
    public function create() {
        return view('register');
    }

    // Create New User
    public function store(Request $request) {
        $formFields = $request->validate([
            'first_name' => ['required', 'min:3'],
            'last_name' => ['required', 'min:3'],
            'phone' => ['required', 'string'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => 'required|confirmed|min:6'
        ]);

        // Hash Password
        $formFields['password'] = bcrypt($formFields['password']);

        // Create User
        Customer::create($formFields);

        return redirect('/');
    }

    // Logout User
    public function logout(Request $request) {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');

    }

    // Authenticate User
    public function authenticate(Request $request) {
        $formFields = $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required'
        ]);

        if(auth()->attempt($formFields)) {
            $request->session()->regenerate();

            return redirect('/');
        }

        return back()->withErrors(['email' => 'Invalid Credentials'])->onlyInput('email');
    }

    public function signup(Request $request, $locale){
        // Apply App Change Language
        $slug = 'signup';
        ChangeAppLanguage::setLocale($locale);
        // end language section

        return view('vendor.voyager.signup', compact('slug')) ;
    }

    public function signupError(Request $request, $locale){
        // Apply App Change Language
        $slug = 'signupError';
        ChangeAppLanguage::setLocale($locale);
        // end language section

        return view('vendor.voyager.signupError', compact('slug')) ;
    }

    public function signupVerifyEmail(Request $request, $locale){
        // Apply App Change Language
        $slug = 'signupVerifyEmail';
        ChangeAppLanguage::setLocale($locale);
        // end language section

        return view('vendor.voyager.signupVerifyEmail', compact('slug')) ;
    }
    public function signupConfirmation(Request $request, $locale){
        // Apply App Change Language
        $slug = 'signupConfirmation';
        ChangeAppLanguage::setLocale($locale);
        // end language section

        return view('vendor.voyager.signupConfirmation', compact('slug')) ;
    }
    
}
