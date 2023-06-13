@extends('voyager::master')

@section('css')
    <style>
        .user-email {
            font-size: .85rem;
            margin-bottom: 1.5em;
        }
        .progress{
            height: 6px;      
        }
        .voyager-x {
            color: red;
            font-size: 14px;
        }
    </style>
@stop

@section('content')
    <div style="background-size:cover; background-image: url({{ Voyager::image( Voyager::setting('admin.bg_image'), voyager_asset('/images/bg.jpg')) }}); background-position: center center;position:absolute; top:0; left:0; width:100%; height:300px;"></div>
    <div style="height:160px; display:block; width:100%"></div>
    <div style="position:relative; z-index:9; text-align:center;">
        <img src="@if( !filter_var(Auth::user()->avatar, FILTER_VALIDATE_URL)){{ Voyager::image( Auth::user()->avatar ) }}@else{{ Auth::user()->avatar }}@endif"
             class="avatar"
             style="border-radius:50%; width:150px; height:150px; border:5px solid #fff;"
             alt="{{ Auth::user()->name }} avatar">
        <h4>{{ ucwords(Auth::user()->name) }}</h4>
        <div class="user-email text-muted">{{ ucwords(Auth::user()->email) }}</div>
        <p>{{ Auth::user()->bio }}</p>
        @if ($route != '')
            <a href="{{ $route }}" class="btn btn-primary">{{ __('voyager::profile.edit') }}</a>
        @endif
        @if(Auth::user()->id != config('common.superadmin_user') && Auth::user()->role_id != config('common.superadmin_role'))
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" data-whatever="@getbootstrap">Change Password</button>
        @endif
    </div>
    
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary" >
                    <h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:absolute;margin-left:90%;margin-top:-20px;color:red">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="update_form" action="{{ route('voyager.users.update',Auth::user()->id) }}{{'?change_password_only='.Auth::user()->id}}">
                        @csrf
                        @method('PUT')
                        @foreach ($errors->all() as $error)
                            <p class="text-danger">{{ $error }}</p>
                        @endforeach 
                        <div class="form-group row"  style=" margin-bottom: 0px;" >
                            <label for="password" class="col-md-4 col-form-label text-md-right"><b>Current Password</b></label>
                            <div class="col-md-8">
                                <input id="password" type="password" class="form-control" name="current_password" autocomplete="current-password">
                            </div>
                        </div>
    
                        <div class="form-group row" style=" margin-bottom: 0px;">
                            <label for="password" class="col-md-4 col-form-label text-md-right">New Password</label>
                            <div class="col-md-8">
                                <span id="popover-password-top" class="hide pull-right block-help"><i class="voyager-info-circled text-danger" aria-hidden="true"></i> Enter a strong password</span>
                                <input id="new_password" type="password" class="form-control" name="new_password" autocomplete="current-password">
                                <div id="popover-password">
                                    <p>Password Strength: <span id="result"> </span></p>
                                    <div class="progress" styl>
                                        <div id="password-strength" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:0%">
                                        </div>
                                    </div>
                                    <ul class="list-unstyled">
                                        <li class=""><span class="low-upper-case" ><i class="icon voyager-x" aria-hidden="true"></i></span>&nbsp; 1 lowercase &amp; 1 uppercase</li>
                                        <li class=""><span class="one-number"><i class="icon voyager-x" aria-hidden="true"></i></span> &nbsp;1 number (0-9)</li>
                                        <li class=""><span class="one-special-char"><i class="icon voyager-x" aria-hidden="true"></i></span> &nbsp;1 Special Character (!@#$%^&*).</li>
                                        <li class=""><span class="eight-character"><i class="icon voyager-x" aria-hidden="true"></i></span>&nbsp; Atleast 8 Character</li>
                                    </ul>
                                </div>
                            </div>                           
                        </div>
    
                        <div class="form-group row" style=" margin-bottom: 0px;">
                            <label for="password" class="col-md-4 col-form-label text-md-right">New Confirm Password</label>
                            <div class="col-md-8">
                                <span id="popover-cpassword" class="hide pull-right block-help"><i class="voyager-info-circle text-danger" aria-hidden="true"></i> Password don't match</span>
                                <input id="new_confirm_password" type="password" class="form-control" name="new_confirm_password" autocomplete="current-password">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary update" id="update">Save </button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('javascript')
    <script>
        @if($errors->all() != null)
            $('#myModal').modal('show');
        @endif
        
        $(document).ready(function() {

            $('#new_password').keyup(function() {
                var password = $('#new_password').val();
                if (checkStrength(password) == false) {
                    $('#update').attr('disabled', true);
                }
            });
            $('#new_confirm_password').blur(function() {
                if ($('#new_password').val() !== $('#new_confirm_password').val()) {
                    $('#popover-cpassword').removeClass('hide');
                    $('#update').attr('disabled', true);
                } else {
                    $('#popover-cpassword').addClass('hide');
                    $('#update').attr('disabled', false);
                }
            });

            function checkStrength(password) {
                var strength = 0;

                //If password contains both lower and uppercase characters, increase strength value.
                if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) {
                    strength += 1;
                    $('.low-upper-case').addClass('text-success');
                    $('.low-upper-case i').removeClass('voyager-x').addClass('voyager-check');
                    $('#popover-password-top').addClass('hide');


                } else {
                    $('.low-upper-case').removeClass('text-success');
                    $('.low-upper-case i').addClass('voyager-x').removeClass('voyager-check');
                    $('#popover-password-top').removeClass('hide');
                }

                //If it has numbers and characters, increase strength value.
                if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/)) {
                    strength += 1;
                    $('.one-number').addClass('text-success');
                    $('.one-number i').removeClass('voyager-x').addClass('voyager-check');
                    $('#popover-password-top').addClass('hide');

                } else {
                    $('.one-number').removeClass('text-success');
                    $('.one-number i').addClass('voyager-x').removeClass('voyager-check');
                    $('#popover-password-top').removeClass('hide');
                }

                //If it has one special character, increase strength value.
                if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) {
                    strength += 1;
                    $('.one-special-char').addClass('text-success');
                    $('.one-special-char i').removeClass('voyager-x').addClass('voyager-check');
                    $('#popover-password-top').addClass('hide');

                } else {
                    $('.one-special-char').removeClass('text-success');
                    $('.one-special-char i').addClass('voyager-x').removeClass('voyager-check');
                    $('#popover-password-top').removeClass('hide');
                }

                if (password.length > 7) {
                    strength += 1;
                    $('.eight-character').addClass('text-success');
                    $('.eight-character i').removeClass('voyager-x').addClass('voyager-check');
                    $('#popover-password-top').addClass('hide');

                } else {
                    $('.eight-character').removeClass('text-success');
                    $('.eight-character i').addClass('voyager-x').removeClass('voyager-check');
                    $('#popover-password-top').removeClass('hide');
                }

                // If value is less than 2
                if (strength < 2) {
                    $('#result').removeClass()
                    $('#password-strength').addClass('progress-bar-danger');

                    $('#result').addClass('text-danger').text('Very Week');
                    $('#password-strength').css('width', '10%');
                } else if (strength == 2) {
                    $('#result').addClass('good');
                    $('#password-strength').removeClass('progress-bar-danger');
                    $('#password-strength').addClass('progress-bar-warning');
                    $('#result').addClass('text-warning').text('Week')
                    $('#password-strength').css('width', '60%');
                    return 'Week'
                } else if (strength == 4) {
                    $('#result').removeClass()
                    $('#result').addClass('strong');
                    $('#password-strength').removeClass('progress-bar-warning');
                    $('#password-strength').addClass('progress-bar-success');
                    $('#result').addClass('text-success').text('Strength');
                    $('#password-strength').css('width', '100%');

                    return 'Strong'
                }

            }

        });    
    </script>
@endsection
