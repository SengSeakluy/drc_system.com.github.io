@extends('voyager::auth.master')
@section('pre_css')
    <style>
        .progress{
            height: 6px; 
            margin-left: 8px; 
        }
        .voyager-x {
            color: red;
            font-size: 14px;
        }
        .list-unstyled li{
            text-align: left;
            margin-bottom: 0;
            margin-top: 3px;
            color: #757c85;
            font-family: Open Sans,sans-serif;
            border-radius: 2px;
            font-size: 12px;
            width: auto;
            padding-left: 8px;
        }
    </style>
@stop
@section('content')
    <div class="login-container" style="top: 37%;">
        <h4>Change your password</h4>
            @foreach($errors->all() as $error)
                <li class="text-danger">{{ $error }}</li>
            @endforeach 
        <form action="{{ url('admin/change-password',['id'=>$id]) }}" method="POST">
            {{ csrf_field() }}
            <div class="form-group form-group-default" id="passwordGroup">
                <label class="col-md-12 control-label" style="padding-left:0px;" for="password">Password <span id="popover-password-top" class="hide pull-right block-help"><i class="voyager-info-circled text-danger" aria-hidden="true"></i> Enter a strong password</span></label>
                <div class="controls">
                    <input id="password" type="password" class="form-control" name="new_password" placeholder="Enter New Password" autocomplete="current-password" required>
                </div>
            </div>
            <div id="popover-password">
                <p style="margin-left: 8px;">Password Strength: <span id="result"> </span></p>
                <div class="progress">
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
            <div class="form-group form-group-default" id="passwordGroup_confirm">
                <label class="col-md-12 control-label" style="padding-left:0px;" for="passwordinput">Confirm new password<span id="popover-cpassword" class="hide pull-right block-help"><i class="voyager-info-circle text-danger" aria-hidden="true"></i> Password don't match</span></label>
                <div class="controls">
                    <input required id="new_confirm_password" type="password" class="form-control" name="new_confirm_password" placeholder="Confirm New Password" autocomplete="current-password">
                </div>
            </div>
            <button type="submit" class="btn btn-block login-button" id="change_password">
                <span class="signin">CHANGE MY PASSWORD</span>
            </button>

        </form>

        <div style="clear:both"></div>
    </div> <!-- .login-container -->
@endsection

@section('post_js')
<script type="text/javascript" src="{{ voyager_asset('js/app.js') }}"></script>
    <script>
        var form = document.forms[0];
        var new_password = document.querySelector('[name="new_password"]');
        var con_password = document.querySelector('[name="new_confirm_password"]');
        new_password.addEventListener('focusin', function(e){
            document.getElementById('passwordGroup').classList.add("focused");
        });
        new_password.addEventListener('focusout', function(e){
            document.getElementById('passwordGroup').classList.remove("focused");
        });
        con_password.addEventListener('focusin', function(e){
            document.getElementById('passwordGroup_confirm').classList.add("focused");
        });
        con_password.addEventListener('focusout', function(e){
            document.getElementById('passwordGroup_confirm').classList.remove("focused");
        });

        $(document).ready(function() {

            $('#password').keyup(function() {
                var password = $('#password').val();
                if (checkStrength(password) == false) {
                    $('#change_password').attr('disabled', true);
                }
            });
            $('#new_confirm_password').blur(function() {
                if ($('#password').val() !== $('#new_confirm_password').val()) {
                    $('#popover-cpassword').removeClass('hide');
                    $('#change_password').attr('disabled', true);
                } else {
                    $('#popover-cpassword').addClass('hide');
                    $('#change_password').attr('disabled', false);
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
