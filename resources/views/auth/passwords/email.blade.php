@extends('frontend.layouts.app')
@section('css')
<style>
    body{
        box-sizing: border-box;
    }
    .banner-img{
        background-image: url(../images/img-17.jpeg);
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
{{--
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
--}}

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
                            <div class="text-center"><h5>{{ __('Reset Password') }}</h5></div>
                            <div class="d-flex justify-content-center">
                                <div class="col">
                                    @if (session('status'))
                                        <div class="alert alert-success" role="alert">
                                            {{ session('status') }}
                                        </div>
                                    @endif

                                    <form method="POST" action="{{ route('password.email') }}">
                                        @csrf

                                        <div class="mb-3">
                                            <label for="email" class="form-label text-md-end">{{ __('Email') }}</label>
                                            <input id="email" type="email" placeholder="Enter Email" class="form-control @error('email') is-invalid @enderror form-input" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            
                                        </div>

                                        <div class="mb-0">
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary"  style="border-radius: 0.625rem;">
                                                    {{ __('Send Password Reset Link') }}
                                                </button>
                                                @guest
                                                    @if (Route::has('login'))
                                                        <a class="nav-link" href="{{ route('login') }}">Login</a>
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
