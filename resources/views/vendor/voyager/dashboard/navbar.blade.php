<style>
    #imageOne{
        margin-top: 15px;
        display: none;
    }
    #imageTwo{
        margin-top: 15px;
        display: none;
    }
    .option ul li{
        padding: 5px;
        cursor: pointer;
    }
    /* .option ul li:hover{
        background-color: orange;
        padding: 5px;
    } */
    .sm-circle {
        border: 1px solid gray;
        /* background-color: yellow; */
        text-align: center;
        color: black;
        vertical-align: middle;
        /* display: table-cell; */
        height: 30px;
        -moz-border-radius:75px;
        -webkit-border-radius: 75px;
        width: 30px;
        position: relative;
    }
    .sm-circle i{
        position: absolute;
        top: 8px;
        left: 12px;
    }
    .circle {
        border: 1px solid gray;
        /* background-color: yellow; */
        text-align: center;
        color: black;
        vertical-align: middle;
        display: table-cell;
        height: 40px;
        -moz-border-radius:75px;
        -webkit-border-radius: 75px;
        width: 40px;
    }
    .navbar .navbar-nav>li {
        float: left;
    }
</style>
<style>
        .voyager .navbar.navbar-default .navbar-right .dropdown-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;

        }

        .voyager .navbar.navbar-default .navbar-right .dropdown2 {
            background: transparent;
            padding: 10px;
            border-radius: 3px;
            width: 140px;
            display: flex;
            justify-content: space-around;
            font-size: 1.2rem;
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

        .voyager .navbar.navbar-default .navbar-right .dropdown-menu2 {
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

        @keyframes openDropDown {
        from { transform: rotateX(50deg); }
        to { transform: rotateX(0deg); }
        }

        @media (max-width: 767px){
            .voyager .navbar.navbar-default .navbar-right .dropdown2 {
                width: 30px;
            }
        }
    </style>
<nav class="navbar navbar-default navbar-fixed-top sticky-top navbar-top navbar-padding" style="background-color:#ffff">
    <div class="header-content-container">
        <div class="navbar-header">
            <button class="hamburger btn-link">
                <span class="hamburger-inner"></span>
            </button>
            @section('breadcrumbs')
            <ol class="breadcrumb hidden-xs">
                @php
                $segments = array_filter(explode('/', str_replace(route('voyager.dashboard'), '', Request::url())));
                $url = route('voyager.dashboard');
                @endphp
                @if(count($segments) == 0)
                    <li class="active"><i class="voyager-boat"></i> {{ __('voyager::generic.dashboard') }}</li>
                @else
                    <li class="active">
                        <a href="{{ route('voyager.dashboard')}}"><i class="voyager-boat"></i> {{ __('voyager::generic.dashboard') }}</a>
                    </li>
                    @foreach ($segments as $segment)
                        @php
                        $url .= '/'.$segment;
                        @endphp
                        @if ($loop->last)
                            <li>{{ ucfirst(urldecode($segment)) }}</li>
                        @else
                            <li>
                                <a href="{{ $url }}">{{ ucfirst(urldecode($segment)) }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            </ol>
            @show
        </div>
        <ul class="nav navbar-nav @if (__('voyager::generic.is_rtl') == 'true') navbar-left @else navbar-right @endif pe-4">
            {{-- <li class="dropdown notification">
                <a href="#" class="text-right" data-toggle="dropdown" role="button"
                   aria-expanded="false" id="notification">
                   <span class="fa-xl">
                    <i class="fa-regular fa-bell"></i>                
                   </span>
                </a>
                   
                <ul class="dropdown-menu dropdown-menu-animated" aria-labelledby="notification">
                    <li>Notification</li>
                    <li class="divider"></li>
                    <li>Notification</li>
                    <li class="divider"></li>
                    <li>Notification</li>
                </ul>
            </li> --}}
            <!-- <li class="">     
                <div id="imageOne">
                    <img src="{{ asset('images/flags/GB.png') }}" width="40px" height="27px"/><span class="hidden-xs ps-2">English</span>
                </div>
                <div id="imageTwo">
                    <img src="{{ asset('images/flags/FR.png') }}" width="40px" height="27px"/><span class="hidden-xs ps-2">Français</span>
                </div>
            </li>
            <li class="dropdown option me-3">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"> 
                        <i class="fa-solid fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li data-opt="1"> English</a></li>
                        <li data-opt="2"> Français</a></li>
                    </ul>
            </li> -->
            <li style="height: 60px; border:none;">
                <span >
                    <img class="" src="/images/flags/united-kingdom.png" alt="" style="height: 27px; margin-top: 16px;"> 
                </span>
            </li>
            <li class="dropdown-container" style="height: 60px; border:none;">
                    <label for="openDropdown" class="dropdown2 mb-0">
                        <span class="hidden-xs">English</span>
                        <span class="text-center"><i class="fas fa-angle-down"></i></span>
                        
                    </label>
                    <input type="checkbox" id="openDropdown" hidden>
                    <div class="dropdown-menu2">
                        <span>
                            <span class="p-0">
                               <img src="/images/flags/united-kingdom.png" alt="" style="width: 23px;"> 
                            </span>
                            <span>English</span> 
                        </span>
                            
                        <span>
                            <span class="p-0">
                                <img src="/images/flags/france.png" style="width: 23px;" alt="">
                            </span>
                            <span>France</span>
                        </span>
                    </div>
            </li>
            <li class="hidden-xs" style="margin-top: 13px; border:none">
                <p class="circle">CC</p>
            </li>
            <li class="hidden-xs" style="margin-left:12px; border:none;">
                <h3 style="font-size: 13px;margin-top:15px;padding:0;margin-bottom:0;">{{ Auth::user()->name }}</h3>
                <p style="font-size: 11px">{{ Auth::user()->role->name }}</p>
            </li>
            <li class="dropdown profile">
                
                <a href="#" class="" data-toggle="dropdown" role="button" aria-expanded="false" style="padding: 15px 0 0 15px;">
                    <p class="sm-circle"><i class="fa-solid fa-ellipsis-vertical" ></i></p>
                </a>
                
                
                {{-- <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> --}}
                   {{-- <img src="{{ $user_avatar }}" class="profile-img">  --}}
                   {{-- <i class="fa-light fa-circle-ellipsis-vertical"></i>
                </a> --}}
                
                <ul class="dropdown-menu dropdown-menu-animated" style="position: absolute!important;">
                    <li class="profile-img">
                        <img src="{{ $user_avatar }}" class="profile-img">
                        <div class="profile-body">
                            <h5>{{ Auth::user()->name }}</h5>
                            <h6>{{ Auth::user()->email }}</h6>
                        </div>
                    </li>
                    <li class="divider"></li>
                    <!-- <li><a href="{{env('APP_URL')}}"><div class="icon voyager-home"> Home Page</div></a></li> -->
                    <?php $nav_items = config('voyager.dashboard.navbar_items'); ?>
                    @if(is_array($nav_items) && !empty($nav_items))
                    @foreach($nav_items as $name => $item)
                    <li {!! isset($item['classes']) && !empty($item['classes']) ? 'class="'.$item['classes'].'"' : '' !!}>
                        @if(isset($item['route']) && $item['route'] == 'voyager.logout')
                        <form action="{{ route('voyager.logout') }}" method="POST">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-block">
                                @if(isset($item['icon_class']) && !empty($item['icon_class']))
                                <i class="{!! $item['icon_class'] !!}"></i>
                                @endif
                                {{__($name)}}
                            </button>
                        </form>
                        @else
                        <a href="{{ isset($item['route']) && Route::has($item['route']) ? route($item['route']) : (isset($item['route']) ? $item['route'] : '#') }}" {!! isset($item['target_blank']) && $item['target_blank'] ? 'target="_blank"' : '' !!}>
                            @if(isset($item['icon_class']) && !empty($item['icon_class']))
                            <i class="{!! $item['icon_class'] !!}"></i>
                            @endif
                            {{__($name)}}
                        </a>
                        @endif
                    </li>
                    @endforeach
                    @endif
                </ul>
            </li>
        </ul>

    </div>
</nav>
<script>
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

    $(document).ready(function(){
        // alert(1);
        $("#imageOne").show();
        $('.option ul li').on('click', function(){
            // alert(1);
            var eThis = $(this);
            opt=eThis.data('opt')
            // alert(opt); 
            if(opt==1){
                $("#imageOne").show();
                $("#imageTwo").hide();
            }else{
                $("#imageOne").hide();
                $("#imageTwo").show();
            }
            
        });
    });
</script>

