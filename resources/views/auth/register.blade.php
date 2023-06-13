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
        /* height: 100vh !important; */
    }
    .card-login{
        background: rgba(255,255,255,.28) !important;
        border-radius: 16px !important;
        box-shadow: 0 4px 30px rgb(0 0 0 / 10%) !important;
        backdrop-filter: blur(15px);
        border: none !important;
        margin: 20px 0px;
    }
    .app-brand{
        background: transparent !important;
        flex-grow: 1 !important;
        /* display: flex !important; */
        justify-content: center;
        align-items: center;
        padding-bottom: 10px;
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
    .form-input, .form-select{
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
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
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
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-login">
                    <div class="card-body">
                        
                        <div class="app-brand text-center">
                            <a href="/"><img src="/images/cambodiapost.png" alt="" width="100px"></a>
                            <h2>CambodiaPost</h2>
                        </div>
                        
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            {{--
                            <div class="mb-3">
                                <label for="name" class="form-label text-md-end">{{ __('Name') }}</label>
                                <input id="name" type="text" placeholder="Enter Name" class="form-control @error('name') is-invalid @enderror form-input" name="name" value="{{ old('name') }}"  autocomplete="name" autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            --}}

                            <div class="mb-3">
                                <label for="email" class="form-label text-md-end">{{ __('Email') }}</label>
                                <input id="email" type="email" placeholder="Enter Your Email" class="form-control @error('email') is-invalid @enderror form-input" name="email" value="{{ old('email') }}" autofocus>
                                @error('email')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="country" class="form-label text-md-end">{{ __('Country') }}</label>
                                <select class="form-select @error('country') is-invalid @enderror" name="country">
                                    <option selected disabled value="">Select</option>
                                    <option value="Cambodia">Cambodia</option>
                                </select>
                                @error('country')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="contactnumber" class="form-label text-md-end">{{ __('Contact Number') }}</label>
                                <input id="contact_number" type="number" placeholder="Enter Your Contact Number" class="form-control @error('contact_number') is-invalid @enderror form-input" name="contact_number" value="{{ old('contact_number') }}">
                                @error('contact_number')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                            

                            <div class="mb-3">
                                <label class="form-label text-md-end">{{ __('Password') }}</label>
                                <input type="password" placeholder="Enter Password" class="form-control @error('password') is-invalid @enderror form-input" name="password">
                                @if($errors->any())
                                    @error('password')
                                        <div class="text-danger">{{$message}}</div>
                                    @enderror
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-md-end">{{ __('Confirm Password') }}</label>
                                <input type="password" placeholder="Enter Confirm Password" class="form-control form-input @error('confirm_password') is-invalid @enderror" name="confirm_password">
                                @error('confirm_password')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" required>
                                    <label class="form-check-label">
                                        I would like to receive new product information and special offers about cambodiapost.
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" required>
                                    <label class="form-check-label">
                                        I acknowledge and accept the 
                                        <a href="#" class="text-decoration-none"> Privacy policy </a>,
                                        <a href="#" class="text-decoration-none"> Website term </a> of use.
                                    </label>
                                </div>
                            </div>

                            <div class="mb-0">
                                <button type="submit" class="btn btn-primary" style="width:100%; border-radius: 0.625rem;">
                                    {{ __('Register') }}
                                </button>
                                @guest
                                    @if (Route::has('login'))
                                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                                    @endif
                                @endguest
                            </div>
                        </form>
                                
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection
