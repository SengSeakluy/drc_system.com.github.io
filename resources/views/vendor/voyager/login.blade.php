@extends('voyager::auth.master')

@section('content')
    <div class="form-container">
        <h2 class="title capitalize-first">{{ __('login.login_to') }}</h2>
        <form action="{{ route('voyager.login','en') }}" class="form" method="POST" id="login-form">
        {{ csrf_field() }}
            <div class="input">
                <label for="email">{{  __('login.email') }}</label>   
                @if($errors->getBag('default')->first('email'))
                    <input class="error" type="text" name="email" id="email" value="{{ old('email') }}">
                    <p class="error mb-0">{{  __('login.email_required') }}</p>
                @else
                    <input type="text" name="email" id="email" value="{{ old('email') }}">
                @endif
                
            </div>
            <div class="input">
                <label for="email">{{  __('login.password') }}</label>
                @if($errors->getBag('default')->first('password'))
                    <input class="error" type="password" name="password" id="password">
                    <p class="error mb-0">{{  __('login.password_required') }}</p>
                @else
                    <input type="password" name="password" id="password">
                @endif

            </div>
            <!-- <a class="forget-password text-decoration-none" href="{{ URL::to('admin/forgot-password',['locals'=>app()->getLocale()]) }}">{{  __('login.forgot_password') }}</a> -->
            <div class="d-flex justify-content-center mt-5">
                <button type="submit" class="login-button mx-auto">
                    <span class="d-flex justify-content-center">
                        <span class="signin align-self-center me-1">{{  __('login.login') }}</span>  
                        <span wire:loading id="login-spinning" class="spinner-border hidden" role="status"></span> 
                    </span>
                </button>                        
            </div>
            <!-- <div class="d-flex justify-content-center mt-3">
                <span>{{  __('login.dont_have_account') }} </span>
                <a href="{{ URL::to('admin/signup',['locals'=>app()->getLocale()]) }}">{{  __('login.create_account') }}</a>
            </div> -->
        </form>


        @if(session('success'))
            <span>{{ session('success') }}</span>
            <span>{{  __('login.new_password') }}</span>
        @endif
    </div>
@endsection