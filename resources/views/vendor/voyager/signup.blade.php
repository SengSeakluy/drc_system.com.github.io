@extends('voyager::auth.master')

@section('content')
    <div class="form-container" style="padding:5rem 0rem;">
        <h5 class="title capitalize-first">{{ __('signup.get_started') }}</h5>
        <form action="" class="form" method="POST" id="d-form">
        {{ csrf_field() }}
            <div class="input">
                <label for="email">{{ __('signup.full_name') }}</label>   
                <input class="" type="text" name="email" id="email" value="">   
            </div>
            
            <div class="input">
                <label for="email"> {{ __('signup.email') }} </label>  
                <input type="text" name="password" id="password">
            </div>
            <div class="input">
                <label for="email">{{ __('signup.password') }}</label> 
                <input type="password" name="password" id="password">       
            </div>
            <style>
                input[type=checkbox]{
                    vertical-align: middle;
                    position: relative;
                }
                label{
                    display: block;
                    font-size: 18px;
                }
            </style>
            <div class="pt-4">
                <label for="checkbox1" class="input"><input id="checkbox1" type="checkbox" style="margin-top: auto; margin-bottom:auto; padding:0; height:1.2rem; width:1.2rem"> {{ __('signup.accept_terms') }} <a class="text text-dark" href="">{{ __('signup.service') }}</a>  {{ __('signup.and') }} <a  class="text text-dark" href="">{{ __('signup.privacy_policy') }}</a> </label>
            </div>
           
            <div class="d-flex justify-content-center mt-5">
                <a href="{{URL::to('admin/signupVerifyEmail',['locals'=>app()->getLocale()])}}" type="submit" class="btn login-button mx-auto" style="border-radius: 0.25rem">
                    <span class="d-flex justify-content-center" >
                        <span class="signin align-self-center me-1"> {{ __('signup.lets_go') }} </span>  
                        <span wire:loading id="login-spinning" class="spinner-border hidden" role="status"></span> 
                    </span>
                </a>                        
            </div>
            <div class="d-flex justify-content-center mt-5" style="font-size: 18px">
                <span>{{ __('signup.already_have_account') }}? <a href="{{URL::to('admin/signin',['locals'=>app()->getLocale()])}}"> {{ __('signup.sign_in') }} </a></span>
                
            </div>
        </form>


        
    </div>
@endsection

