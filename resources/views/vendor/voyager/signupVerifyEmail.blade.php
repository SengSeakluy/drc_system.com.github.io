@extends('voyager::auth.master')

<style>

    .inputCode div input{
        width: 100%;
    }
</style>

@section('content')
    <div class="form-container" style="padding:8rem 0rem;">
        <div>
            
        </div>
        <h1 class="title capitalize-first">{{ __('signupVerifyEmail.verify_email') }}</h1>
        <p class="pt-3" style="font-size: 18px">
            {{ __('signupVerifyEmail.send_email_description') }}
        </p>

        <div class="d-flex mt-5 inputCode">
            <div>
                <input class="text text-center" type="text" style=" height:16vh; font-size:300%"  maxlength="1" > 
            </div>
            <div>            
                <input class="text text-center" type="text" style=" height:16vh; font-size:300%" maxlength="1" > 
            </div>
            <div>            
                <input class="text text-center" type="text" style=" height:16vh; font-size:300%" maxlength="1" > 
            </div>
            <div class="text mx-4" style="width: 200px;
            height: 1px;
            margin: auto;
            
            border: solid 1px #979797;">

            </div>
            <div>           
                 <input class="text text-center" type="text" style=" height:16vh; font-size:300%" maxlength="1" > 
            </div>
            <div>           
                 <input class="text text-center" type="text"  style=" height:16vh; font-size:300%" maxlength="1" > 
            </div>
            <div>           
                 <input class="text text-center" type="text" style=" height:16vh; font-size:300%" maxlength="1" > 
            </div>
        </div>


        <div  style="width:100% ">
            
            <p class="text pt-4" style="font-size: 18px; ">{{ __('signupVerifyEmail.receive_confirmation_code') }} ? <a href="#">{{ __('signupVerifyEmail.resend') }}</a></p>
    
        </div>
      

       
    </div>
   
@endsection