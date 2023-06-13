@extends('frontend.layouts.app')
@section('css')
<style>
    body{
        box-sizing: border-box;
    }
    .banner-img{
        /* background-image: url(../images/img-17.jpeg); */
        background-repeat: no-repeat;
        background-size: cover;
        height: 100vh !important;
    }
    .card-login{
        background: rgba(255,255,255,.28) !important;
        border-radius: 16px !important;
        box-shadow: 0 4px 30px rgb(0 0 0 / 10%) !important;
        backdrop-filter: blur(15px);
        border: none !important;
    }
    .app-brand{
        background: transparent !important;
        flex-grow: 1 !important;
        /* display: flex !important; */
        justify-content: center;
        align-items: center;
        padding-bottom: 20px;
    }
    .form-label::before{
        display: inline-block;
        margin-right: 4px;
        color: #ff6b72;
        font-size: 14px;
        font-family: SimSun, sans-serif;
        line-height: 1;
        content: '*';
    }
    .form-input{
        box-shadow: 0 0 0 1000px rgba(255,255,255,.28) inset!important;
        background-clip: border-box !important;
        border-radius: 0.625rem !important;
    }
</style>
@stop

@section('content')
<div class="banner-img">
    <div class="container d-flex flex-column justify-content-center h-100">
        <div class="row justify-content-center w-100">
            <div class="col-lg-6 col-xl-6 col-md-8 col-12">
                <div class="card card-login">
                    <div class="card-body">
                        <div class="my-4 px-4 w-100">
                            <div class="app-brand text-center">
                                <a href="/"><img src="/images/cambodiapost.png" alt="" width="100px"></a>
                                <h1>CambodiaPost</h1>
                            </div>
                            <div class="d-flex justify-content-center">
                                <div class="col">
                                    <form method="POST" action="{{ route('login') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="email" class="form-label text-md-end">{{ __('Email') }}</label>
                                            <input id="email" type="email" placeholder="Enter Email" class="form-control @error('email') is-invalid @enderror form-input" name="email" value="{{ old('email') }}" autocomplete="email" autofocus>
                                            @error('email')
                                                <div class="text-danger">{{$message}}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="password" class="form-label text-md-end">{{ __('Password') }}</label>
                                            <input id="password" placeholder="Enter Password" type="password" class="form-control @error('password') is-invalid @enderror form-input" name="password" autocomplete="current-password">
                                            @error('password')
                                                <div class="text-danger">{{$message}}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3 form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="remember">
                                                {{ __('Remember Me') }}
                                            </label>
                                        </div>

                                        <div class="mb-0">
                                            <button type="submit" class="btn btn-primary" style="width:100%; border-radius: 0.625rem;">
                                                Login
                                            </button>
                                            <div class="d-flex">
                                                @if (Route::has('password.request'))
                                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                                        {{ __('Forgot Your Password?') }}
                                                    </a>
                                                @endif

                                                @guest
                                                    @if (Route::has('register'))
                                                        <a class="btn btn-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                                    @endif
                                                @endguest
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection