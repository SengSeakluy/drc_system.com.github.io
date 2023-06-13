@php
    $edit = !is_null($dataTypeContent->getKey());
    $add  = is_null($dataTypeContent->getKey());
@endphp

@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .select2-container--default .select2-selection--multiple {
            height: auto;
            margin: auto;
            overflow: hidden;
        }
        select.form-control + span{
            height: auto !important;
        }
        select.form-control + span .selection .select2-selection{
            padding: 2px 1rem !important;
        }
        
        :root {
            --main-text: rgb(33, 37, 41);
            --secondary-text: #606060;
            --input-bg: #f9f9f9;
        }
        .voyager input[type=file]{
            width: 100% !important;
        }

        .back {
            text-decoration: none;
            display: flex;
            gap: 10px;
            align-items: center;
            color: var(--main-text);
            padding-left: 21px;
        }

        input[type="text"],
        input[type="date"],
        input[type="email"],
        input[type="number"],
            {
            background-color: var(--input-bg);
        }
        .btn-add{
            width: 274px;
            height: 56px;
            background-color: #4880ff;
            border: none;
            border-radius: 12px;
            opacity: 0.9;
        }
        .btn-add:hover {
            background-color: #3659ac;
        }
        .btn-cancel{
            width: 274px;
            height: 56px;
            background-color: white;
            border: none;
            border-radius: 12px;
            opacity: 0.9;
        }
        .qty:focus{
            border-color: rgb(28, 28, 184)
        }
       
        .select2-container--default .select2-selection--single {
            height: 3.5rem;
            border-style: solid;
            --tw-bg-opacity: 1;
            background-color: rgb(229 231 235 / var(--tw-bg-opacity));
            --tw-text-opacity: 1;
            border: 1px solid #ced4da !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 10px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: 7px !important;
        }
        .header {
            margin-top: 60px;
            text-align: right;
        }

        @media only screen and (min-width: 1000px) {
            .side {
                width: 50%;
                gap: 20px;
            }
        }
        @media only screen and (max-width: 1200px) {
            .header {
                margin: 0px !important;
                text-align: left;
            }
        }
        .border-card{
            border-radius: 14px !important;
        }
    </style>
@stop

@section('page_title', __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('page_header')
    @can('browse', $dataTypeContent)
        <a href="{{ route('voyager.'.$dataType->slug.'.index') }}" class="back">
            <h5><i class="fa-solid fa-arrow-left"></i></h4>
            <h4>Back</h4>
        </a>
    @endcan
    <h1 class="ps-4">{{ __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular') }}</h1>
    @include('voyager::multilingual.language-selector')
@stop

@section('content')
    <div class="page-content edit-add container-fluid mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-12 col-xl-3 col-lx-3">
                                <h3 class="header">{{$dataType->getTranslatedAttribute('display_name_singular')}} Info</h3>
                            </div>
                       
                            <div class="col-12 col-md-12 col-xl-9 col-lx-9">
                                <!-- form start -->
                                <form role="form"
                                        class="form-edit-add"
                                        action="{{ $edit ? route('voyager.'.$dataType->slug.'.update', $dataTypeContent->getKey()) : route('voyager.'.$dataType->slug.'.store') }}"
                                        method="POST" enctype="multipart/form-data">
                                    <!-- PUT Method if we are editing -->
                                    @if($edit)
                                        {{ method_field("PUT") }}
                                    @endif

                                    <!-- CSRF TOKEN -->
                                    {{ csrf_field() }}

                                    <div class="card-body">

                                        @if (count($errors) > 0)
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <!-- Adding / Editing -->
                                        @php
                                            $dataTypeRows = $dataType->{($edit ? 'editRows' : 'addRows' )};
                                        @endphp

                                        @foreach($dataTypeRows as $row)
                                            <!-- GET THE DISPLAY OPTIONS -->
                                            @php
                                                $display_options = $row->details->display ?? NULL;
                                                if ($dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')}) {
                                                    $dataTypeContent->{$row->field} = $dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')};
                                                }
                                            @endphp
                                            @if (isset($row->details->legend) && isset($row->details->legend->text))
                                                <legend class="text-{{ $row->details->legend->align ?? 'center' }}" style="background-color: {{ $row->details->legend->bgcolor ?? '#f0f0f0' }};padding: 5px;">{{ $row->details->legend->text }}</legend>
                                            @endif

                                            <div class="form-group @if($row->type == 'hidden') hidden @endif col-md-{{ $display_options->width ?? 12 }} {{ $errors->has($row->field) ? 'has-error' : '' }}" @if(isset($display_options->id)){{ "id=$display_options->id" }}@endif>
                                                {{ $row->slugify }}
                                                <div class="col-12 col-md-12 col-lx-8 col-xl-8">
                                                    <div class="mt-3">
                                                        <label class="control-label" for="name">{{ $row->getTranslatedAttribute('display_name') }}</label>
                                                        @include('voyager::multilingual.input-hidden-bread-edit-add')
                                                        @if ($add && isset($row->details->view_add))
                                                            @include($row->details->view_add, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'view' => 'add', 'options' => $row->details])
                                                        @elseif ($edit && isset($row->details->view_edit))
                                                            @include($row->details->view_edit, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'view' => 'edit', 'options' => $row->details])
                                                        @elseif (isset($row->details->view))
                                                            @include($row->details->view, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'action' => ($edit ? 'edit' : 'add'), 'view' => ($edit ? 'edit' : 'add'), 'options' => $row->details])
                                                        @elseif ($row->type == 'relationship')
                                                            @include('voyager::formfields.relationship', ['options' => $row->details])
                                                        @else
                                                            {!! app('voyager')->formField($row, $dataType, $dataTypeContent) !!}
                                                        @endif

                                                        @foreach (app('voyager')->afterFormFields($row, $dataType, $dataTypeContent) as $after)
                                                            {!! $after->handle($row, $dataType, $dataTypeContent) !!}
                                                        @endforeach
                                                        @if ($errors->has($row->field))
                                                            @foreach ($errors->get($row->field) as $error)
                                                                <span class="help-block">{{ $error }}</span>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                        <div style="display:block">
                                            <div class="col-md-12">
                                                <div class="col-12 col-md-12 col-lx-8 col-xl-8">
                                                    <div class="mt-3">
                                                        <div style="text-align: center;">
                                                            @section('submit-buttons')
                                                                <button type="submit" class="btn-add text-white">{{ __('voyager::generic.add') }} now</button>
                                                            @stop
                                                            @yield('submit-buttons')
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="col-12 col-md-12 col-lx-8 col-xl-8">
                                                    <div class="mt-3">
                                                        <div style="text-align: center;">
                                                            <button class="text-center btn-cancel">{{ __('voyager::generic.cancel') }}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div><!-- panel-body -->

                                    {{--
                                    <div class="panel-footer">
                                        @section('submit-buttons')
                                            <button type="submit" class="btn btn-primary save">{{ __('voyager::generic.save') }}</button>
                                        @stop
                                        @yield('submit-buttons')
                                    </div>
                                    --}}
                                </form>

                                <div style="display:none">
                                    <input type="hidden" id="upload_url" value="{{ route('voyager.upload') }}">
                                    <input type="hidden" id="upload_type_slug" value="{{ $dataType->slug }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-danger" id="confirm_delete_modal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="voyager-warning"></i> {{ __('voyager::generic.are_you_sure') }}</h4>
                </div>

                <div class="modal-body">
                    <h4>{{ __('voyager::generic.are_you_sure_delete') }} '<span class="confirm_delete_name"></span>'</h4>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                    <button type="button" class="btn btn-danger" id="confirm_delete">{{ __('voyager::generic.delete_confirm') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Delete File Modal -->
@stop

@section('javascript')
    <script>
        var params = {};
        var $file;

        function deleteHandler(tag, isMulti) {
          return function() {
            $file = $(this).siblings(tag);

            params = {
                slug:   '{{ $dataType->slug }}',
                filename:  $file.data('file-name'),
                id:     $file.data('id'),
                field:  $file.parent().data('field-name'),
                multi: isMulti,
                _token: '{{ csrf_token() }}'
            }

            $('.confirm_delete_name').text(params.filename);
            $('#confirm_delete_modal').modal('show');
          };
        }

        $('document').ready(function () {
            $('.toggleswitch').bootstrapToggle();

            //Init datepicker for date fields if data-datepicker attribute defined
            //or if browser does not handle date inputs
            $('.form-group input[type=date]').each(function (idx, elt) {
                if (elt.hasAttribute('data-datepicker')) {
                    elt.type = 'text';
                    $(elt).datetimepicker($(elt).data('datepicker'));
                } else if (elt.type != 'date') {
                    elt.type = 'text';
                    $(elt).datetimepicker({
                        format: 'L',
                        extraFormats: [ 'YYYY-MM-DD' ]
                    }).datetimepicker($(elt).data('datepicker'));
                }
            });

            @if ($isModelTranslatable)
                $('.side-body').multilingual({"editing": true});
            @endif

            $('.side-body input[data-slug-origin]').each(function(i, el) {
                $(el).slugify();
            });

            $('.form-group').on('click', '.remove-multi-image', deleteHandler('img', true));
            $('.form-group').on('click', '.remove-single-image', deleteHandler('img', false));
            $('.form-group').on('click', '.remove-multi-file', deleteHandler('a', true));
            $('.form-group').on('click', '.remove-single-file', deleteHandler('a', false));

            $('#confirm_delete').on('click', function(){
                $.post('{{ route('voyager.'.$dataType->slug.'.media.remove') }}', params, function (response) {
                    if ( response
                        && response.data
                        && response.data.status
                        && response.data.status == 200 ) {

                        toastr.success(response.data.message);
                        $file.parent().fadeOut(300, function() { $(this).remove(); })
                    } else {
                        toastr.error("Error removing file.");
                    }
                });

                $('#confirm_delete_modal').modal('hide');
            });
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@stop
