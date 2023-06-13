<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" dir="{{ __('voyager::generic.is_rtl') == 'true' ? 'rtl' : 'ltr' }}">
<head>
    <title>@yield('page_title', setting('admin.title') . " - " . setting('admin.description'))</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="assets-path" content="{{ route('voyager.voyager_assets') }}"/>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@200;300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link href="/progressbar/themes/blue/pace-theme-minimal.css" rel="stylesheet" />
    <link href="/css/custome-style.css" rel="stylesheet" />
    <link href="/fontawesome-free-6.2.0-web 2/css/all.min.css" rel="stylesheet" />
    <link href="/fontawesome-free-6.2.0-web 2/css/svg-with-js.min.css" rel="stylesheet" />
    <!-- if condition below set for reload page when user click go to back button to make sure select-list still selected -->
    @if( 
        (isset($dataType) && $dataType->name == 'merchant' && isset(request()->id)) || 
        (isset($page_list_of_data) && $page_list_of_data == true) || 
        (isset($product_item_price_page) && $product_item_price_page == true)
    )
    <script>
        // reload page when user click go to back button to make sure select-list still selected
        const [entry] = performance.getEntriesByType("navigation");
        if (entry["type"] === "back_forward"){
            location.reload();
        }
    </script>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="/progressbar/pace.min.js" type='text/javascript'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Favicon -->
    <?php $admin_favicon = Voyager::setting('admin.icon_image', ''); ?>
    @if($admin_favicon == '')
        <link rel="shortcut icon" href="{{ voyager_asset('images/logo-icon.png') }}" type="image/png">
    @else
        <link rel="shortcut icon" href="{{ Voyager::image($admin_favicon) }}" type="image/png">
    @endif



    <!-- App CSS -->
    <link rel="stylesheet" href="{{ voyager_asset('css/app.css') }}">
    <link href="/css/override-voyager.css?v=2" rel="stylesheet" />
    <link href="/jquery-ui-1.12.1/jquery-ui.css" rel="stylesheet" />
    <script src="/js/Chart.min.js"></script>
    <script src="/js/chartisan_chartjs.umd.js"></script>
    
    @yield('css')
    @stack('css')
    @if(__('voyager::generic.is_rtl') == 'true')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.4.0/css/bootstrap-rtl.css">
        <link rel="stylesheet" href="{{ voyager_asset('css/rtl.css') }}">
    @endif
    
    <!-- Few Dynamic Styles -->
    <style type="text/css">
        .voyager .side-menu .navbar-header {
            background:{{ config('voyager.primary_color','#22A7F0') }};
            border-color:{{ config('voyager.primary_color','#22A7F0') }};
        }
        .widget .btn-primary{
            border-color:{{ config('voyager.primary_color','#22A7F0') }};
        }
        .widget .btn-primary:focus, .widget .btn-primary:hover, .widget .btn-primary:active, .widget .btn-primary.active, .widget .btn-primary:active:focus{
            background:{{ config('voyager.primary_color','#22A7F0') }};
        }
        .voyager .breadcrumb a{
            color:{{ config('voyager.primary_color','#22A7F0') }};
        }
        .form-search .collapse:not(.show), #resources .collapse:not(.show) {
            display: none !important;
        }

        .side-menu-container {
            width: 100%;
        }

        #site.fade:not(.show),
        #admin.fade:not(.show),
        #resources.fade:not(.show),
        #commands.fade:not(.show),
        #logs.fade:not(.show) {
            opacity: 1 !important;
        }
        .toast-success{
            background-color: #51a351 !important;
        }
        .toast-error{
            background-color: #bd362f !important;
        }
        .modal.fade{
            opacity: 1;
        }
        .modal{
            padding-top:15%; 
        }
    
        .toast-warning{
            background-color: #bd362f !important;
        }
    </style>

    @if(!empty(config('voyager.additional_css')))<!-- Additional CSS -->
        @foreach(config('voyager.additional_css') as $css)<link rel="stylesheet" type="text/css" href="{{ asset($css) }}">@endforeach
    @endif
    <link href="/css/override-boostrap.css" rel="stylesheet" />
    @yield('head')
</head>

<body class="voyager @if(isset($dataType) && isset($dataType->slug)){{ $dataType->slug }}@endif">

<div id="voyager-loader">
    <?php $admin_loader_img = Voyager::setting('admin.loader', ''); ?>
    @if($admin_loader_img == '')
        <img src="{{ voyager_asset('images/logo-icon.png') }}" alt="Voyager Loader">
    @else
        <img src="{{ Voyager::image($admin_loader_img) }}" alt="Voyager Loader">
    @endif
</div>

<?php
if (\Illuminate\Support\Str::startsWith(Auth::user()->avatar, 'http://') || \Illuminate\Support\Str::startsWith(Auth::user()->avatar, 'https://')) {
    $user_avatar = Auth::user()->avatar;
} else {
    $user_avatar = Voyager::image(Auth::user()->avatar);
}
?>

<div class="app-container">
    <div class="fadetoblack visible-xs"></div>
    <div class="row content-container">
        @include('voyager::dashboard.navbar')
        @include('voyager::dashboard.sidebar')
        <script>
            (function(){
                    var appContainer = document.querySelector('.app-container'),
                        sidebar = appContainer.querySelector('.side-menu'),
                        navbar = appContainer.querySelector('nav.navbar.navbar-top'),
                        loader = document.getElementById('voyager-loader'),
                        hamburgerMenu = document.querySelector('.hamburger'),
                        sidebarTransition = sidebar.style.transition,
                        navbarTransition = navbar.style.transition,
                        containerTransition = appContainer.style.transition;

                    sidebar.style.WebkitTransition = sidebar.style.MozTransition = sidebar.style.transition =
                    appContainer.style.WebkitTransition = appContainer.style.MozTransition = appContainer.style.transition =
                    navbar.style.WebkitTransition = navbar.style.MozTransition = navbar.style.transition = 'none';

                    if (window.innerWidth > 768 && window.localStorage && window.localStorage['voyager.stickySidebar'] == 'true') {
                        appContainer.className += ' expanded no-animation';
                        loader.style.left = (sidebar.clientWidth/2)+'px';
                        hamburgerMenu.className += ' is-active no-animation';
                    }

                   navbar.style.WebkitTransition = navbar.style.MozTransition = navbar.style.transition = navbarTransition;
                   sidebar.style.WebkitTransition = sidebar.style.MozTransition = sidebar.style.transition = sidebarTransition;
                   appContainer.style.WebkitTransition = appContainer.style.MozTransition = appContainer.style.transition = containerTransition;
            })();
        </script>
        <!-- Main Content -->
        <div class="container-fluid">
            <div class="side-body padding-top">
                @yield('page_header')
                <div id="voyager-notifications"></div>
                @yield('content')
            </div>
        </div>
    </div>
</div>
@include('voyager::partials.app-footer')

<!-- Javascript Libs -->


<script type="text/javascript" src="{{ voyager_asset('js/app.js') }}"></script>

<script>
    @if(Session::has('alerts'))
        let alerts = {!! json_encode(Session::get('alerts')) !!};
        helpers.displayAlerts(alerts, toastr);
    @endif

    @if(Session::has('message'))

    // TODO: change Controllers to use AlertsMessages trait... then remove this
    var alertType = {!! json_encode(Session::get('alert-type', 'info')) !!};
    var alertMessage = {!! json_encode(Session::get('message')) !!};
    var alerter = toastr[alertType];

    if (alerter) {
        alerter(alertMessage);
    } else {
        toastr.error("toastr alert-type " + alertType + " is unknown");
    }
    @endif
</script>
<script src="/jquery-ui-1.12.1/jquery-ui.js"></script>

<!-- add dropdown -->
<script> 
    $(document).ready(function (){
        $('.dropdownAdd').on('click', function(){
            if($(this).next().hasClass('hidden')){
                $(this).next().removeClass( "hidden" );
            }else{
                $('.dropdownMenuAdd').removeClass('hidden')
                $('.dropdownMenuAdd').addClass('hidden')
                $(this).next().addClass( "hidden" );
            }
        });
        $('#dropdownAdd').on('click', function(){
            if($('#dropdownMenuAdd').data('hidden')){
                $('#dropdownMenuAdd').removeClass( "hidden" );
            }else{
                $('#dropdownMenuAdd').addClass( "hidden" );
            }
            $('#dropdownMenuAdd').data('hidden', !$('#dropdownMenuAdd').data('hidden')) 
        })
    })

    $(document).on("click", function(event){
        if(!$(event.target).hasClass('dropdownAdd')){
            $('.dropdownMenuAdd').removeClass('hidden')
            $('.dropdownMenuAdd').addClass('hidden')
        }
    });

</script>

{{--
@if(Auth::user()->id != config('common.superadmin_user') && Auth::user()->role_id != config('common.superadmin_role'))
    @include('vendor.voyager.product_list.shopping-cart')
@endif
--}}

@include('voyager::media.manager')
@yield('javascript')
@stack('javascript')
@if(!empty(config('voyager.additional_js')))<!-- Additional Javascript -->
    @foreach(config('voyager.additional_js') as $js)<script type="text/javascript" src="{{ asset($js) }}"></script>@endforeach
@endif

</body>
</html>
