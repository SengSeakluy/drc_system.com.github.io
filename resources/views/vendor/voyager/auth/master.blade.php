<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" dir="{{ __('voyager::generic.is_rtl') == 'true' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="none" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="admin login">
    <title>@yield('title', 'Admin - '.Voyager::setting("admin.title"))</title>
    <link rel="stylesheet" href="{{ voyager_asset('css/app.css') }}">
    <link rel="stylesheet" href="/bootstrap-5.0.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- <link rel="stylesheet" href="/css/font-awesome6.all.min.css" /> -->

    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans" rel="stylesheet">
    @if (__('voyager::generic.is_rtl') == 'true')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.4.0/css/bootstrap-rtl.css">
        <link rel="stylesheet" href="{{ voyager_asset('css/rtl.css') }}">
        <link rel="stylesheet" href="/public/bootstrap-5.0.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="public/css/font-awesome6.min.css">
        <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->
    @endif

    <style>
        html, body {
            font-family: Nunito Sans;
        }
        .login {
            display: flex;
            min-height: 100%;
        }
        .login .presentation {
            width: 35%;
            color: #fff;
            background-size: cover;
            background-position-y: center;
            display: flex;
            align-items: center;
            flex-direction: column;
            background-image: url("/images/sign-in/sign-in.33e61e5a.png");
        }
        .login .container {
            width: 70%;
            margin-top: 8px;
        }
        .login .presentation .wrapper {
            margin: auto;
            padding: 0 4rem;
        }
        .login .presentation .wrapper .tittle {
            font-size: 3rem;
        }
        .login .presentation .wrapper .description {
            margin-top: 1.75rem;
            font-size: 1.25rem;
        }
        .login .presentation .logo-wrapper {
            width: 14rem;
            margin-bottom: 5.7rem;
            align-self: start;
            margin-left: 4rem;
        }
        .login .presentation .logo-wrapper img {
            max-width: 100%;
            height: auto;
        }
        .login .container .form-container {
            width: 100%;
            padding: 6rem 4rem;
            display: flex;
            flex-direction: column;
            align-self: center;
            align-items: center;
            max-width: 37rem;
            margin: auto;
        }
        .login .container .form-container .title{
            font-size: 2.25rem;
            font-weight: 700;
        }
        .login .container .form-container .form{
            max-width: 37rem;
            width: 100%;
            margin-top: 1.5rem;
        }

        .login .container .form-container .form>div.input{
            display: flex;
            flex-direction: column;
            margin-top: 2.85rem;
        }        
        .login .container .form-container .form>div.input label{
            text-align: left;
            color: #606060;
            font-weight: 700;
        }
        .login .container .form-container .form input {
            margin-top: 0.75rem;
            padding: 0 1rem;
            height: 3.5rem;
            border: 1px solid #e4e4e7;
            background-color: #f5f6fa;
            border-radius: 0.25rem;
        }
        .login .container .form-container .form .forget-password {
            display: block;
            text-align: right;
            margin-top: 1rem;
            color: #202224;
            opacity: .6;
        }
        .login .container .form-container .form .login-button {
            background-color: rgb(72, 128, 255);
            background-position: center center;
            transition: background 0.8s ease 0s;
            font-size: 1.125rem;
            line-height: 1.75rem;
            border-radius: 0.75rem;
            border: none;
            padding: 1rem 1.5rem;
            width: 16rem;
            color: #fff;
            font-weight: 700;
        }
        .login .container .form-container .form p.error{
            color: #e02020;
        }
        .login .container .form-container .form input.error {
            border-color: #e02020;
        }
        .message.error {
            text-align: left;
            color: #e02020;
            opacity: .65;
        }
        .capitalize-first::first-letter {
            text-transform: capitalize;
        }
        @media screen and (max-width: 768px) {
            .login {
                flex-direction: column;
            }
            .login .presentation{
                padding: 3rem 0;
                width: 100%;
            }
            .login .presentation .logo-wrapper {
                margin-top: 3rem;
                margin-bottom: 1rem;
            }
            .login .container {
                width: 100%;
            }
        }
    </style>

    <style>
        .login .container .dropdown-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;

        }

        .login .container .dropdown {
            background: transparent;
            padding: 10px;
            border-radius: 3px;
            width: 140px;
            display: flex;
            justify-content: space-around;
            font-size: 0.9rem;
            cursor: pointer;
        }

        .fa-angle-down {
            position: relative;
            font-size: 0.9rem;
            transition: transform 0.3s ease;
        }

        .rotate-dropdown-arrow {
                transform: rotate(-180deg);
        }

        .login .container .dropdown-menu2 {
            display: none;
            flex-direction: column;
            border-radius: 4px;
            margin-top: 130px;
            width: 140px;
            box-shadow: 0 0 5px -1px rgba(0, 0, 0, 0.3);
            background: #fafafa;
            transform-origin: top left;
            box-shadow: 0 9px 40px 4px rgba(0, 0, 0, .11);
            background-color: #fff;
            position: absolute;
            
        }

        .dropdown-menu2 span {
            padding: 10px;
            flex-grow: 1;
            width: 100%;
            box-sizing: border-box;
            text-align: center;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .dropdown-menu2 span:hover {
            background: #eee;
        }

        #openDropdown:checked + .dropdown-menu2 {
            display: flex;
            animation: openDropDown 0.4s ease 0s 1 forwards;
        }
        .back-home-btn{
            background-color: rgb(72, 128, 255);
            background-position: center center;
            transition: background 0.8s ease 0s;
            font-size: 1.125rem;
            line-height: 1.75rem;
            border-radius: 0.75rem;
            border: none;
            padding: 1rem 1.5rem;
            width: 16rem;
            color: #fff;
            font-weight: 700;
            text-decoration: none;
            text-align: center;
            margin-top: 20px;
        }

        @keyframes openDropDown {
        from { transform: rotateX(50deg); }
        to { transform: rotateX(0deg); }
        }
    </style>
    

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
</head>
<body>
    <div class="login">
        <div class="presentation">
            <!-- <div class="wrapper">
                <h2 class="tittle capitalize-first">{{ __('login.main_title') }}</h2>
                <p class="description capitalize-first">
                    {{ __('login.sub_title') }}
                </p>
            </div> -->
            <!-- <div class="logo-wrapper">
                <img src="https://merchant.dev.shoprunback.com/assets/srb-logo-white.0be2be88.png" alt="shoprunback logo" data-v-4566077b="">
            </div> -->
        </div>
        <div class="container">
            <div class="d-flex justify-content-end items-content-center">
                @include('voyager::multilingual.languages')
            </div>
            <!-- content -->
            @yield('content')
        </div>
    </div>


<script type="text/javascript" src="{{ voyager_asset('js/app.js') }}"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
        <script src="/bootstrap-5.0.2/js/bootstrap.min.js"></script>
        <script src="/bootstrap-5.0.2/js/bootstrap.bundle.min.js"></script>
        <!-- <script src="/jquery-ui-1.12.1/jquery-ui.js"></script> -->
    <script>
        $(document).ready(function() {
            $('#login-form').submit(function() {
                $('#login-spinning').removeClass("hidden")
            });
        });




        const $drowdownArrow = document.querySelector('.fa-angle-down');
        const $checkbox = document.getElementById('openDropdown');
        const $dropdownMenu = document.querySelector('.dropdown-menu2');

        $checkbox.addEventListener('change', () => {
        $drowdownArrow.classList.toggle('rotate-dropdown-arrow');
        });

        $dropdownMenu.addEventListener('click', (e) => {
        $checkbox.checked = false;
        // setting checked to false won't trigger 'change'
        // event, manually dispatch an event to rotate
        // dropdown arrow icon
        $checkbox.dispatchEvent(new Event('change'));
        });

    </script>
        
</body>

</html>
