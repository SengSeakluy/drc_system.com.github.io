@extends('voyager::master')

@section('page_title', __('voyager::generic.'.(isset($dataTypeContent->id) ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .rec {
            width: 274px;
            height: 56px;
            padding: 16px 97px 16px 98px;
            opacity: 0.9;
            border-radius: 12px;
            background-color: #4880ff;
            border: none;
            transition: 300ms;
        }

        .rec:hover {
            background-color: #3659ac;
        }

        .panel-footer {
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .panel-body .form-group .select2 {
            border-left: 1px solid #ced4da !important;
        }

        .panel-body .form-group input {
            border-left: 1px solid #ced4da !important;
        }
        
        .back {
            text-decoration: none;
            display: flex;
            gap: 10px;
            align-items: center;
            color: var(--main-text);
        }
        .app-container.expanded .content-container .side-menu{
            width: 240px !important;
        }
        .page-title1{
            display: inline-block;
            font-size: 1.5rem;
            font-weight: 700;
            position: relative;
        }
        
    </style>

@stop

@section('page_header')
    
    <div class="container-fluid">
        <a href="{{ route('voyager.'.$dataType->slug.'.index') }}" class="back">
            <h5><i class="fa-solid fa-arrow-left"></i></h5>
            <h4>Back</h4>
        </a>

        <h1 class="page-title1">
            <i class="{{ $dataType->icon }}"></i>
            {{ __('voyager::generic.'.(isset($dataTypeContent->id) ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular') }}
        </h1>
    </div>
    @include('voyager::multilingual.language-selector')

@stop

@section('content')

    <div class="page-content edit-add container-fluid">
        <form class="form-edit-add" role="form"
              action="@if(!is_null($dataTypeContent->getKey())){{ route('voyager.'.$dataType->slug.'.update',$dataTypeContent->getKey())}}@if(isset(Request()->name)){{'?parent_name='.Request()->name }} @endif @else{{ route('voyager.'.$dataType->slug.'.store') }}@if(isset(Request()->name)){{'?parent_name='.Request()->name }} @endif @endif @if(isset(Request()->read)){{'&read='.Request()->read }}@endif"
              method="POST" enctype="multipart/form-data" autocomplete="off">
            
            @if(isset($dataTypeContent->id))
                {{ method_field("PUT") }}
            @endif
            {{ csrf_field() }}
            @php 
                $selectedMerchantList = $z1AppAdminMerchant = [];
            @endphp
            @if(Auth::user()->id != config('common.superadmin_user') && Auth::user()->role_id != config('common.superadmin_role'))
                @if($dataTypeContent->id == 1 && $dataTypeContent->role_id ==1)
                    Session::flash('error', 'Sensitive Case');
                    <script type="text/javascript">
                        window.location = "/admin/users";
                    </script>
                @endif

            @endif
            
            <div class="row">
                <div class="panel panel-bordered">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="panel-body">
                        <div class="col-lg-4 col-md-12">
                            <h3 class="header">{{ $dataType->getTranslatedAttribute('display_name_singular') }} Info</h3>
                        </div>
                        <div class="col-lg-8 col-md-12 col-12">
                            
                            <div class="form-group">
                                <label class="mt-3" for="name">{{ __('voyager::generic.name') }}</label>
                                <span class="text-danger">*</span>
                                <input type="text" class="form-control" id="name" name="name" placeholder="{{ __('voyager::generic.name') }}"
                                    value="{{ old('name', $dataTypeContent->name ?? '') }}">
                            </div>
                            <div class="form-group">
                                <label class="mt-3" for="email">{{ __('voyager::generic.email') }}</label>
                                <span class="text-danger">*</span>
                                <input type="email" class="form-control" id="email" name="email" placeholder="{{ __('voyager::generic.email') }}"
                                    value="{{ old('email', $dataTypeContent->email ?? '') }}">
                            </div>
                            @php 
                                $super_admin = (Auth::user()->id == config('common.superadmin_user') && Auth::user()->role_id == config('common.superadmin_role'));
                            @endphp
                            @if($super_admin)
                                <div class="form-group">
                                    <label class="mt-3" for="pas)sword">{{ __('voyager::generic.password') }}</label>
                                    <span class="text-danger">*</span>
                                    @if(isset($dataTypeContent->password))
                                        <br>
                                        <small>{{ __('voyager::profile.password_hint') }}</small>
                                    @endif
                                    <input type="password" class="form-control" id="password" name="password" value="" autocomplete="new-password">
                                </div>
                            @endif
                            @if(!isset(Request()->name))                             
                                @can('editRoles', $dataTypeContent)
                                    <div class="form-group">
                                        <label class="mt-3" for="default_role">{{ __('voyager::profile.role_default') }}</label>
                                        <span class="text-danger">*</span>
                                        
                                        @php
                                            $dataTypeRows = $dataType->{(isset($dataTypeContent->id) ? 'editRows' : 'addRows' )};

                                            $row     = $dataTypeRows->where('field', 'user_belongsto_role_relationship')->first();
                                            $options = $row->details;
                                        @endphp
                                        @include('voyager::formfields.relationship')
                                    </div>
                                    {{--
                                    <div class="form-group">
                                        <label for="additional_roles">{{ __('voyager::profile.roles_additional') }}</label>
                                        @php
                                            $row     = $dataTypeRows->where('field', 'user_belongstomany_role_relationship')->first();
                                            $options = $row->details;
                                        @endphp
                                        @include('voyager::formfields.relationship')
                                    </div>
                                    --}}
                                @endcan
                            @endif
                            @php
                                if (isset($dataTypeContent->locale)) {
                                    $selected_locale = $dataTypeContent->locale;
                                } else {
                                    $selected_locale = config('app.locale', 'en');
                                }

                                $selected_timezone = config('app.client_timezone');
                                if (isset($dataTypeContent->timezone)) {
                                    $selected_timezone = $dataTypeContent->timezone;
                                }
                            @endphp                       

                            <div class="form-group">
                                <label class="mt-3" for="timezone">Timezone</label>
                                <select class="form-control select2 required" id="timezone" name="timezone">
                                    @foreach (config('transaction.available_timezone') as $timezone)
                                    <option value="{{ $timezone }}"
                                    {{ ($timezone == $selected_timezone ? 'selected' : '') }}>{{ $timezone }}</option>
                                    @endforeach
                                </select>
                            </div>
                                
                            @if(!isset(Request()->name))
                                @if(isset($dataTypeContent->avatar))
                                    <img src="{{ filter_var($dataTypeContent->avatar, FILTER_VALIDATE_URL) ? $dataTypeContent->avatar : Voyager::image( $dataTypeContent->avatar ) }}" style="width:200px; height:auto; clear:both; display:block; padding:2px; border:1px solid #ddd; margin-bottom:10px;" />
                                @endif
                                <input type="file" data-name="avatar" name="avatar">
                                        
                                <div class="panel-footer mt-3">
                                    <button type="submit" class="rec text-white">
                                        {{ __('voyager::generic.save') }}
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <iframe id="form_target" name="form_target" style="display:none"></iframe>
        <form id="my_form" action="{{ route('voyager.upload') }}" target="form_target" method="post" enctype="multipart/form-data" style="width:0px;height:0;overflow:hidden">
            {{ csrf_field() }}
            <input name="image" id="upload_file" type="file" onchange="$('#my_form').submit();this.value='';">
            <input type="hidden" name="type_slug" id="type_slug" value="{{ $dataType->slug }}">
        </form>
    </div>

@stop

@section('javascript')

    <script>
        $('document').ready(function () {
            $('.toggleswitch').bootstrapToggle();
            setMerchantIDSession();

            // red marker for merchant_id
            $('.required + .select2').css('border-left', '3px solid red');
        });

    </script>
    
@stop
