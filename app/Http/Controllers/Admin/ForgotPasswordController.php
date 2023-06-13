<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\sendEmailResetPassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use App\CustomClasses\ChangeAppLanguage;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    public function forgotPassword(Request $request, $locale){
        $slug = 'forgot-password';
        ChangeAppLanguage::setLocale($locale);
        return view('vendor.voyager.forgot-password',compact('slug'));
    }

    //reset password 
    public function resetPassword(Request $request,$locale){
        $slug = 'reset-password';
        ChangeAppLanguage::setLocale($locale);
        $request->validate([
            'email' => 'email:rfc,dns',
        ]);
        $user =User::whereEmail($request->email)->first();
        if($user == null){
            return redirect()->back()->withErrors('We can\'t find a user with that email address.');
        }
        $this->send_mail($user);
        return redirect()->back()
                ->with('success','Instructions to reset your password have been sent to : ')
                ->with('email',$user->email)
                ->with('slug',$slug);
    }

      //send email
    public function send_mail($user){
        $to_email = $user->email;
        Mail::to($to_email)->send(new sendEmailResetPassword($user));
    }

    public function changePassword($id,$locale){
        // $slug = 'change-password';
        // ChangeAppLanguage::setLocale($locale);
        try {
            $id=Crypt::decrypt($id);
        } catch (\Throwable $th) {
            abort(403, 'Page Not Found.');
        }
        $active_id=Auth::user()->id??"";
        if($id === $active_id){
            return redirect('/admin/signin/'.(Session::get('local') ?? 'en'));
        }
        return view('vendor.voyager.change-password',compact('id'));
        // return view('vendor.voyager.change-password',compact('id','slug'));
    }
    public function updatePassword(Request $request,$id,$locale){  
            // $slug = 'update-password';
            // ChangeAppLanguage::setLocale($locale);
            $message=[
                'regex' => 'The :attribute must contain at least one number and both uppercase and lowercase letters and one special character.'
            ];
            $validation=Validator::make($request->all(),[
                'new_password' => ['required','min:8','regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/'],
                'new_confirm_password' => ['required','same:new_password'],     
            ],$message);
            if($validation->fails()){
                return redirect()->back()->withErrors($validation)->withInput();
            }
            $user = User::find($id);                   
            $user->password = Hash::make($request->new_password);
            $user->save();
                
            return redirect('/admin/signin/'.(Session::get('local') ?? 'en'))
                    ->with('success',"Password has been change successfuly.");
                    // ->with('slug',$slug);
    }
}
