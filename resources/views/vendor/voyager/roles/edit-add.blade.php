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

    <div class="page-content container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <!-- form start -->
                        <form class="form-edit-add" role="form"
                            action="@if(isset($dataTypeContent->id)){{ route('voyager.'.$dataType->slug.'.update',($read ?? false)? $dataTypeContent->id.'&'.$read : $dataTypeContent->id) }}@else{{ route('voyager.'.$dataType->slug.'.store') }}@endif"
                            method="POST" enctype="multipart/form-data">

                            <!-- PUT Method if we are editing -->
                            @if(isset($dataTypeContent->id))
                                {{ method_field("PUT") }}
                            @endif

                            <!-- CSRF TOKEN -->
                            {{ csrf_field() }}

                            <div class="panel-body">

                                @if (count($errors) > 0)
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div class="col-md-4">
                                <h3 class="header">{{ $dataType->getTranslatedAttribute('display_name_singular') }} Info</h3>
                            </div>
                                <div class="col-md-8">
                                @foreach($dataType->addRows as $row)
                                    <div class="mt-3">
                                    @if($row->type == 'relationship')
                                        @php
                                            $dataRelationShip = $row['details'];
                                            $is_merchant_id = isset($dataRelationShip->column)?1:0;
                                        @endphp
                                        @if(!$is_merchant_id)
                                            <label class="control-label" for="name">{{ $row->getTranslatedAttribute('display_name') }}</label>
                                            @if ($row->required)
                                                <span class="text-danger">*</span>
                                            @endif
                                            @include('voyager::formfields.relationship', ['options' => $row->details])
                                        @else
                                            @if(Auth::user()->id == config('common.superadmin_user') && Auth::user()->role_id == config('common.superadmin_role'))
                                                <label class="control-label" for="name">{{ $row->getTranslatedAttribute('display_name') }}</label>
                                                @if ($row->required)
                                                    <span class="text-danger">*</span>
                                                @endif
                                                @include('voyager::formfields.relationship', ['options' => $row->details])
                                            @endif
                                        @endif
                                    @else
                                        <div class="form-group" style="width: 50%">
                                            <label for="name">{{ $row->getTranslatedAttribute('display_name') }}</label>
                                            @if ($row->field != 'is_z1payment_technical')
                                            @if ($row->required)
                                                <span class="text-danger">*</span>
                                            @endif
                                            @endif
                                            {!! Voyager::formField($row, $dataType, $dataTypeContent) !!}
                                        </div>
                                    @endif
                                    </div>
                                @endforeach

                                <label for="permission">{{ __('voyager::generic.permissions') }}</label><br>
                                <a href="#" class="permission-select-all">{{ __('voyager::generic.select_all') }}</a> / <a href="#"  class="permission-deselect-all">{{ __('voyager::generic.deselect_all') }}</a>
                                <ul class="permissions checkbox">
                                    <?php
                                        $role_permissions = (isset($dataTypeContent)) ? $dataTypeContent->permissions->pluck('key')->toArray() : [];
                                        $role_filter_merchant = (isset($dataTypeContent->merchant_list)) ? (array) json_decode($dataTypeContent->merchant_list) : [];
                                    $filter_merchant = [];
                                    if (isset($role_filter_merchant)) {
                                        foreach ($role_filter_merchant as $index_merchant => $merchant_data) {
                                            $filter_merchant[] = $index_merchant;
                                        }
                                    }
                                    ?>

                                    @php
                                        $selectedMerchantList = [];
                                        if (Auth::user()->merchant_id == 0) {
                                            // filter data based on selected merchant
                                            $roleInfo = TCG\Voyager\Models\Role::where('id', Auth::user()->role_id)->first();
                                        
                                            // if has configure merchant filter data
                                            if ($roleInfo->merchant_list) {
                                                foreach ((array) json_decode($roleInfo->merchant_list) as $index_merchant => $merchant) {
                                                    $selectedMerchantList[] = $index_merchant;
                                                }
                                            }
                                        }
                                    @endphp
                                    @foreach(Voyager::model('Permission')->all()->groupBy('table_name') as $table => $permission)
                                    <div class="mt-3">
                                        <li>
                                            @if(Auth::user()->id == config('common.superadmin_user') && Auth::user()->role_id == config('common.superadmin_role'))
                                                <input type="checkbox" id="{{$table}}" class="permission-group">
                                                <label for="{{$table}}"><strong>{{\Illuminate\Support\Str::title(str_replace('_',' ', $table))}}</strong></label>
                                            @else
                                                @if(!in_array($table, array('menus','settings')))
                                                    <input type="checkbox" id="{{$table}}" class="permission-group">
                                                    <label for="{{$table}}"><strong>{{\Illuminate\Support\Str::title(str_replace('_',' ', $table))}}</strong></label>
                                                @endif
                                            @endif
                                            <ul>
                                                @foreach($permission as $perm)
                                                    @if(Auth::user()->id != config('common.superadmin_user') && Auth::user()->role_id != config('common.superadmin_role') && in_array($table, array('','menus','settings')) && $perm->key != 'browse_admin')
                                                        @php break; @endphp
                                                    @endif
                                                    @if(Auth::user()->id != config('common.superadmin_user') && Auth::user()->role_id != config('common.superadmin_role') && ($perm->key == 'edit_orders' || $perm->key == 'add_orders' || $perm->key == 'delete_orders' || $perm->key == 'edit_transactions' || $perm->key == 'add_transactions' || $perm->key == 'delete_transactions' || $perm->key == 'edit_order_items' || $perm->key == 'add_order_items' || $perm->key == 'delete_order_items'))
                                                        @php break;@endphp
                                                    @endif
                                                    <li>
                                                        <input type="checkbox" id="permission-{{$perm->id}}" name="permissions[{{$perm->id}}]" class="the-permission" value="{{$perm->id}}" @if(in_array($perm->key, $role_permissions)) checked @endif>
                                                        <label for="permission-{{$perm->id}}">{{\Illuminate\Support\Str::title(str_replace('_', ' ', $perm->key))}}</label>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    </div>
                                    @endforeach
                                </ul>
                            </div><!-- panel-body -->
                            <div class="panel-footer">
                                <button type="submit" class="rec text-white">{{ __('voyager::generic.save') }}</button>
                            </div>
                        </form>

                        <iframe id="form_target" name="form_target" style="display:none"></iframe>
                        <form id="my_form" action="{{ route('voyager.upload') }}" target="form_target" method="post"
                            enctype="multipart/form-data" style="width:0;height:0;overflow:hidden">
                            {{ csrf_field() }}
                            <input name="image" id="upload_file" type="file"
                                onchange="$('#my_form').submit();this.value='';">
                            <input type="hidden" name="type_slug" id="type_slug" value="{{ $dataType->slug }}">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script>
        $('document').ready(function () {
            $('.toggleswitch').bootstrapToggle();

            $('.permission-group').on('change', function(){
                $(this).siblings('ul').find("input[type='checkbox']").prop('checked', this.checked);
            });

            $('.permission-select-all').on('click', function(){
                $('ul.permissions').find("input[type='checkbox']").prop('checked', true);
                return false;
            });

            $('.permission-deselect-all').on('click', function(){
                $('ul.permissions').find("input[type='checkbox']").prop('checked', false);
                return false;
            });

            function parentChecked(){
                $('.permission-group').each(function(){
                    var allChecked = true;
                    $(this).siblings('ul').find("input[type='checkbox']").each(function(){
                        if(!this.checked) allChecked = false;
                    });
                    $(this).prop('checked', allChecked);
                });
            }

            parentChecked();

            $('.the-permission').on('change', function(){
                parentChecked();
            });

            // enabled and disabled merchant list from merchant role
            var valueType = $('select[name="type"]').val();
            checkType(valueType);

            $('select[name="type"]').change(function(e) {
                var type = $(this).val();
                console.log(type);
                checkType(type)
            });
            // end

            // red marker for merchant_id
            $('.required + .select2').css('border-left', '3px solid red');
        });

        function checkType(valueType){
            if (valueType) {
                if (valueType == 1) {
                    $(".merchant_list").css("display","none");
                    $(".toggle").css("pointer-events","none");
                    $(".toggle").addClass("off");
                } else {
                    $(".merchant_list").css("display","block");
                    $(".toggle").css("pointer-events","auto");
                }
            }
        }
    </script>
@stop
