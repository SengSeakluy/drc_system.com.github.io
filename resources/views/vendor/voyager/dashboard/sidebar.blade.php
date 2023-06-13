<style>
    .logo {
        line-height: 60px;
    }

    .expanded .small-logo {
        display: none;
    }

    .small-logo {
        display: inline-block;
        height: 60px;
        text-align: center;
        width: 60px;
    }

    .side-menu:hover .small-logo {
        display: none;
    }

    .small-logo img {
        display: inline-block;
        max-height: 50%;
        max-width: 50%;
        position: relative;
        top: -1px;
    }

    .big-logo {
        display: none;
        padding: 5px 45px;
    }

    .big-logo img {
        width: 100%
    }
    .expanded .big-logo{
        display: block !important;
    }

    .side-menu:hover .big-logo {
        display: block !important;
    }
    .side-menu-scroller{
        overflow: auto;
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none; /* Firefox */
    }
    /* Hide scrollbar for Chrome, Safari and Opera */
    .side-menu-scroller::-webkit-scrollbar {
        display: none;
    }

    .search{
        width: 100%;
        position: relative;
    }
    .search .myInput {
        /* background-image: url("/images/search/3917754.png"); */
        /* background-position: 10px 12px;
        background-repeat: no-repeat; */
        width: 100%;
        font-size: 16px;
        padding: 5px 12px 5px 40px;
        border: 1px solid #ddd;
        margin-bottom: 12px;
    }
    .search i{
        position: absolute;
        top: 10px;
        left: 10px;
        font-size: 18px;
    }

    /* .expanded .myInput{

    } */

    /* input:not(.avoidme) { background-color: green; } */

    /* .myInput:not(.expanded){
        display: block;
    } */

</style>
<div class="d-flex side-menu sidebar-inverse" >
    <!-- <div id="side-menu-item" class="border-end" style="width:60px; height:100%;">
        <div class="merchent mt-3" id="render">
        </div>
    </div> -->
    <div class="navbar navbar-default d-block side-menu-scroller"
     role="navigation">
        <div class="side-menu-container">
            <div class="navbar-header" style="background-color: #fff !important;">
                {{-- <a class="navbar-brand" href="{{ route('voyager.dashboard') }}"> --}}

                    {{-- <div class="logo-icon-container">

                        @if($admin_logo_img == '')
                            <img src="{{ voyager_asset('images/logo-icon-light.png') }}" alt="Logo Icon">
                        @else
                            <img src="{{ Voyager::image($admin_logo_img) }}" alt="Logo Icon">
                        @endif
                    </div>
                    <div class="title">{{Voyager::setting('admin.title', 'VOYAGER')}}</div>

                </a> --}}
                <div class="logo">
                    <?php $admin_logo_img = Voyager::setting('admin.icon_image', ''); ?>
                    <a href="{{ route('voyager.dashboard') }}">
                        <div class="small-logo">
                            @if($admin_logo_img == '')
                                <img src="{{ voyager_asset('images/logo-icon-light.png') }}" alt="Logo Icon">
                            @else
                                <img src="{{ Voyager::image($admin_logo_img) }}" alt="Logo Icon">
                            @endif
                        </div>
                        <div class="big-logo">
                            <img src="{{ Voyager::image($admin_logo_img) }}" alt="Logo Icon">
                        </div>
                    </a>
                </div>
                </div><!-- .navbar-header -->

            {{-- <div class="panel widget center bgimage" style="box-shadow: none;">
                <!-- <div class="dimmer"></div> -->
                <div class="panel-content">
                    <img src="{{ $user_avatar }}" class="avatar" alt="{{ Auth::user()->name }} avatar">
                    <h4 style="color:black">{{ ucwords(Auth::user()->name) }}</h4>
                    <p>{{ Auth::user()->email }}</p>

                    <a href="{{ route('voyager.profile') }}" class="btn btn-primary">{{ __('voyager::generic.profile') }}</a>
                    <div style="clear:both"></div>
                </div>
            </div> --}}

        </div>
        <div id="adminmenu">
            <div class="search">
                <input type="text" class="myInput" name="myInput" id="myInput" onkeyup="myFunction()" placeholder="Search for names.." title="Type in a name">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
            <admin-menu :items="{{ menu('admin', '_json') }}"></admin-menu>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#adminmenu').find('ul:eq(0)').attr("id", "myUL");
    });

    function myFunction() {
        var input, filter, ul, li, a, i, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();

        var li = document.getElementById("myUL").childNodes;
        // console.log(li);
        for (i = 0; i < li.length; i++) {
            a = li[i].getElementsByTagName("a")[0];
            txtValue = a.textContent || a.innerText;
            console.log(txtValue.toUpperCase().indexOf(filter) > -1);
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }
        }

    }

    $(function(){
        $.ajax({
            url: '/getMerchant',
            type: 'get',
            datatype: 'html',
            success: function(response){
                $('#render').html(response);
            }
        });
    });
</script>

