@extends('voyager::auth.master')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="height: 100%; margin-top: -45px;">
    <div class="my-auto form-container">
        @if(session('success'))
            <div class="description text-center">
                <h2 class="title text-center">Password Reset</h2>
                <span class="mx-auto">{{ session('success')}}</span>
                <div class="text-center"><strong>{{ session('email') }}</strong></div>
                
                <span >If you don't receive it, please <strong> contact suport</strong></span></span>
            </div>
        @else
            <div class="description" style="text-align: center;">
                <h2 class="title text-center">Forgot password?</h2>
                <span >We'll send you an email with instruction to reset it.</span>
            </div>
        @endif
        @if(session('success'))
            <a href="{{route('voyager.login')}}" class="back-home-btn">Back to Login</a>
        @else
            <form id="login-form" action="{{ URL::to('admin/reset-password',['locals'=>app()->getLocale()]) }}" method="POST" class="form" style="margin-top: 10px;">
                {{ csrf_field() }}
                <div class="input" style="margin-top: 10px;">
                    <label>{{ __('voyager::generic.email') }}</label>
                    @if($errors->getBag('default')->first('email'))
                    <input class="error" type="text" name="email" id="email" value="{{ old('email') }}">
                        <p class="error mb-0">{{  __('login.email_required') }}</p>
                    @else
                        <input type="text" name="email" id="email" value="{{ old('email') }}">
                    @endif
                </div>
                <div class="d-flex justify-content-center mt-5">
                    <button type="submit" class="login-button mx-auto">
                        <span class="d-flex justify-content-center">
                            <span>Reset Password</span> 
                            <span wire:loading id="login-spinning" class="spinner-border hidden" role="status"></span> 
                        </span>
                    </button>                        
                </div>
            </form>
        @endif
        <a href="{{route('voyager.login')}}" class="float-right text-decoration-none" style="margin-top: 20px;"><i class="fa-solid fa-arrow-left"></i>&nbsp;&nbsp;Back to Login</a>
        <div class="mb-4" style="clear:both"></div>
        @if(!$errors->isEmpty())
            <div class="alert alert-danger">
                <ul class="list-unstyled">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div> <!-- .login-container -->
</div>
@endsection

@section('post_js')

    <script>
        var form = document.forms[0];
        var email = document.querySelector('[name="email"]');
        var password = document.querySelector('[name="password"]');
        email.focus();
        document.getElementById('emailGroup').classList.add("focused");

        // Focus events for email and password fields
        email.addEventListener('focusin', function(e){
            document.getElementById('emailGroup').classList.add("focused");
        });
        email.addEventListener('focusout', function(e){
            document.getElementById('emailGroup').classList.remove("focused");
        });


    </script>
@endsection
