@extends('voyager::master')

@section('page_title', __('voyager::generic.view').' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('css')
    <style>
        .page-content.container-fluid>.row {
            min-width: 425px;
        }
        .page-header-container{
            margin-top: 0px;
            margin-left: 30px;
        }

        .card-container {
            border-radius: 14px;
            border:1px solid  rgba(0, 0, 0, 0.1);
            background-color: #fff;
            margin-top: 20px;
            padding: 35px 50px 50px 50px;
        }
        .card-container .card-header {
            background-color: transparent;
            border: none;
        }
        .card-container .card-header .card-tabs{
            display: flex;
            justify-content: start;
        }
        .card-container .card-header .card-tabs .tab{
            font-size: 16px;
            color: rgba(0, 0, 0, 0.5);
            margin-right: 50px;
        }
        .card-container .card-header .card-tabs .tab button{
            background-color: transparent;
            border: none;
            padding: 0;
        }
        .card-container .card-header .card-tabs .tab:hover{
            color: #4880ff;
        }
        .card-container .card-header .card-tabs .tab.active{
            color: #4880ff;
            border-bottom: 1px solid #4880ff;
        }

        #details .item {
            margin-bottom: 25px;
        }
        #details .item p{
            color: #606060;
        }
        #details .item .detail {
            /* height: 50px; */
            width: 430px;
            border-radius: 4px;
            border: 1px solid #d5d5d5;
            background-color: #f5f6fa;
            padding: 13px;
        }
        #details .item .detail-empty, .detail-relation{
            height: 50px;
            width: 430px;
            border-radius: 4px;
            border: 1px solid #d5d5d5;
            background-color: #f5f6fa;
            padding: 13px;
        }
        #details .item .detail-status.processing{
            background-color: rgb(98 38 239 / 0.5);
            color: #6226ef;
            border-radius: 4px;
            padding: 6px 16px 5px;
            display:inline-block;

        }
        #details .item .detail-items img{
            width: 60px;
            height: 60px;
            border-radius: 5px;
            /* object-fit: contain; */
        }
        #details .item .detail-items span {
            display: inline-block;
            margin-left: 20px;
        }
        #shipment-timeline .column  {
            margin-bottom: 25px;
        }
        #shipment-timeline .column  p{
            color: #606060;
        }
        #shipment-timeline .column  .detail {
            height: 50px;
            width: 430px;
            border-radius: 4px;
            border: 1px solid #d5d5d5;
            background-color: #f5f6fa;
            padding: 13px 0 0 16px;
        }
        #shipment-timeline .column  .detail-status.processing{
            background-color: rgb(98 38 239 / 0.5);
            color: #6226ef;
            border-radius: 4px;
            padding: 6px 16px 5px;
            display:inline-block;

        }
        #shipment-timeline .column .detail-link {
            height: fit-content !important;
        }
        .timeline .item{
            display: flex;
        }
        .timeline .item .status{
            width: 16px;
            height: 16px;
            border-radius: 100%;
            background-color: #bfbfbf;
            z-index: 1;
            color: white;
            margin-top: 2px;
            position: absolute;
        }
        .timeline .item .status.checked{
            background-color: #53d37e;
        }
        .timeline .item .info{
            display: flex;
            justify-content: start;
            flex-wrap: wrap;
            margin-left: 22px;
            margin-bottom: 35px;
        }
        .timeline .item .info div:last-child {
            max-width: 70%;
        }
        .timeline .item .status>i {
            font-size: 10px;
            position: absolute;
            margin-left: 2.5px;
            margin-top: 2px;
        }
        .timeline .item .status>i.voyager-check:before{
            font-size: 12px;
        }
        .timeline .item::before{
            position: relative;
            content: '';
            border: 1px solid rgba(0, 0, 0, 0.1);
            width: 1px;
            height: inherit;
            margin-top: 2px;
            left: 7px;
            z-index: 0;
        }
        .timeline .item:last-child:before{
            content: '';
            border: 1px solid transparent;

        }
        .timeline .item .date {
            margin-right: 50px;
        }
        .timeline .item p{
            color: #606060;
            font-size: 10px;
            margin-bottom: 0;
        }
        .timeline .item p span{
            color: #000;
        }
        .card-container .card-body .info {
            display: flex;
            justify-content: space-between;
        }
        .card-container .card-body .info label {
            color: rgba(0, 0, 0, 0.65);
        }

        @media (max-width: 769Px){
            #details .item .detail ,#shipment-timeline .column  .detail{
                width: 90%;
            }
            #details .item .detail-empty{
                width: 90%;
            }
            .page-content .container-fluid > .row{
                min-width: 600px;
            }
        }
        .colum-url {
            display: flex;
            align-items: center;
        }
        .detail-link{
            padding: 10px !important;
        }
        .copy-icon{
            font-size: 30px;
            cursor: pointer;
            padding-left: 10px;
        }
    </style>
@stop


@section('page_header')
    <div class="page-header-container">
        <div class="row">
            <div class="col col-md-8" style="display: contents">
                @can('browse', $dataTypeContent)
                    @if(request()->name && isset(request()->name))
                        @if(isset(request()->merchant_name_id))
                            <a href="{{ route('voyager.'.strToLower(explode('/',request()->name)[0]).'.show',['id'=>explode('/',request()->name)[1],'name'=>request()->merchant_name_id]) }}" class="btn btn-warning">
                        @else
                            <a href="{{ route('voyager.'.strToLower(explode('/',request()->name)[0]).'.show',['id'=>explode('/',request()->name)[1]]) }}" class="btn btn-warning">
                        @endif
                    @else
                        <a href="{{ route('voyager.'.$dataType->slug.'.index') }}" style="text-decoration: none;display:flex;height:inherit;">
                    @endif
                            <i class="fa-sharp fa-solid fa-arrow-left" style="font-size: 1.25rem;color:black; margin-top:4px;"></i>
                            <h1 style="margin-left: 10px; font-size:1.5rem;" class="text-dark">Back</h1>
                        </a>
                @endcan
            </div>
        </div>
        <div class="row">
            <div class="col col-md-12" style="padding:0px;">
                <h1>{{ ucfirst($dataType->getTranslatedAttribute('display_name_singular')) }} # {{ $dataTypeContent->uuid }}&nbsp;</h1>
            </div> 
        </div>
    </div>

    @include('voyager::multilingual.language-selector')
@stop
@section('content')

    <!-- New Custom view -->
    <div class="page-content container-fluid">
        <div class="row">
            <div class="col-md-12 col-lg-12 col-xl-12">
                <div class="card-container">
                    <div class="card-header">
                        <div class="card-tabs">
                            <div class="tab active" data-target="details"><button>Details</button></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="card-body-contents" >
                            <div id="details">
                                <div class="item">
                                    @foreach($dataType->readRows as $row)
                                        @php
                                            if ($dataTypeContent->{$row->field.'_read'}) {
                                                $dataTypeContent->{$row->field} = $dataTypeContent->{$row->field.'_read'};
                                            }
                                        @endphp

                                        <div class="item">
                                            <p>{{ $row->getTranslatedAttribute('display_name') }}</p>
                                            @if (isset($row->details->view))
                                                @include($row->details->view, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'action' => 'read', 'view' => 'read', 'options' => $row->details])
                                            @elseif($row->type == "image")
                                                <div class="">
                                                    <div class="detail-items">
                                                        <div><img class="img-responsive" src="{{ filter_var($dataTypeContent->{$row->field}, FILTER_VALIDATE_URL) ? $dataTypeContent->{$row->field} : Voyager::image($dataTypeContent->{$row->field}) }}"></div>
                                                    </div>
                                                </div>
                                            @elseif($row->type == 'multiple_images')
                                                @if(json_decode($dataTypeContent->{$row->field}))
                                                    <div class="">
                                                        <div class="detail-items">
                                                            <div>
                                                                @foreach(json_decode($dataTypeContent->{$row->field}) as $file)
                                                                    <img class="img-responsive" src="{{ filter_var($file, FILTER_VALIDATE_URL) ? $file : Voyager::image($file) }}">
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="">
                                                        <div class="detail-items">
                                                            <div><img class="img-responsive" src="{{ filter_var($dataTypeContent->{$row->field}, FILTER_VALIDATE_URL) ? $dataTypeContent->{$row->field} : Voyager::image($dataTypeContent->{$row->field}) }}"></div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @elseif($row->type == 'relationship')
                                                <div class="detail-relation">
                                                    <span> @include('voyager::formfields.relationship', ['view' => 'read', 'options' => $row->details]) </span>
                                                </div>
                                            @elseif($row->type == 'select_dropdown' && property_exists($row->details, 'options') && !empty($row->details->options->{$dataTypeContent->{$row->field}}))
                                                @if($row->field=='status')
                                                    <div><span @if( $dataTypeContent->{$row->field}==1 || $dataTypeContent->{$row->field}=='SUCCESS' ) style="background-color:{{ '#2ecc71' }} @elseif ( $dataTypeContent->{$row->field}=='0' || $dataTypeContent->{$row->field} != 'WAITING') style="background-color:{{'#fa2a00'}} @else style="background-color:{{'#FFCC00'}} @endif;padding:10px;border-radius:5px;color:white;font-size:10px;"><?php echo $row->details->options->{$dataTypeContent->{$row->field}};?></span></div>
                                                @else 
                                                    <div class="detail"><span><?php echo $row->details->options->{$dataTypeContent->{$row->field}};?></span></div>
                                                @endif
                                            @elseif($row->type == 'select_multiple')
                                                @if(property_exists($row->details, 'relationship'))
                                                    @foreach(json_decode($dataTypeContent->{$row->field}) as $item)
                                                        {{ $item->{$row->field}  }}
                                                    @endforeach
                                                @elseif(property_exists($row->details, 'options'))
                                                    @if (!empty(json_decode($dataTypeContent->{$row->field})))
                                                        @foreach(json_decode($dataTypeContent->{$row->field}) as $item)
                                                            @if (@$row->details->options->{$item})
                                                                {{ $row->details->options->{$item} . (!$loop->last ? ', ' : '') }}
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <div class=""><span>{{ __('voyager::generic.none') }}</span></div>
                                                    @endif
                                                @endif
                                            @elseif($row->type == 'date' || $row->type == 'timestamp')
                                                @if ($row->field == 'deleted_at')
                                                    <div class="detail-empty">
                                                        <span>{{ $dataTypeContent->{$row->field} }}</span>
                                                    </div>
                                                @else
                                                <div class="detail">
                                                    @if ( property_exists($row->details, 'format') && !is_null($dataTypeContent->{$row->field}) )
                                                        <span>{{ \Carbon\Carbon::parse($dataTypeContent->{$row->field})->formatLocalized($row->details->format) }} </span>
                                                    @else
                                                        @if (isset($dataTypeContent->{$row->field}))
                                                            <span> {{ \Carbon\Carbon::parse($dataTypeContent->{$row->field})->setTimezone(config('app.client_timezone'))->toDateTimeString() }} </span>
                                                        @else
                                                            <span> {{ \Carbon\Carbon::parse($dataTypeContent->{$row->field})->setTimezone(config('app.client_timezone'))->toDateTimeString() }} </span>
                                                        @endif
                                                    @endif
                                                </div>
                                                @endif
                                            @elseif($row->type == 'checkbox')
                                                @if(property_exists($row->details, 'on') && property_exists($row->details, 'off'))
                                                    @if($dataTypeContent->{$row->field})
                                                        <div class=""><span class="label label-info">{{ $row->details->on }}</span></div>
                                                    @else
                                                        <div class=""><span class="label label-primary">{{ $row->details->off }}</span></div>
                                                    @endif
                                                @else
                                                    @if ($dataTypeContent->{$row->field} == 1)
                                                        <span class="label label-primary p-2">Enable</span>
                                                    @else
                                                        <span class="label label-danger p-2">Disable</span>
                                                    @endif
                                                @endif
                                            @elseif($row->type == 'color')
                                                <span class="badge badge-lg" style="background-color: {{ $dataTypeContent->{$row->field} }}">{{ $dataTypeContent->{$row->field} }}</span>
                                            @elseif($row->type == 'coordinates')
                                                @include('voyager::partials.coordinates')
                                            @elseif($row->type == 'rich_text_box')
                                                <div class="detail-relation">
                                                    @include('voyager::multilingual.input-hidden-bread-read')
                                                    <span>{!! $dataTypeContent->{$row->field} !!}</span>
                                                </div>
                                            @elseif($row->type == 'file')
                                                @if(json_decode($dataTypeContent->{$row->field}))
                                                    @foreach(json_decode($dataTypeContent->{$row->field}) as $file)
                                                        <a href="{{ Storage::disk(config('voyager.storage.disk'))->url($file->download_link) ?: '' }}">
                                                            {{ $file->original_name ?: '' }}
                                                        </a>
                                                        <br/>
                                                    @endforeach
                                                @else
                                                    <a href="{{ Storage::disk(config('voyager.storage.disk'))->url($row->field) ?: '' }}">
                                                        {{ __('voyager::generic.download') }}
                                                    </a>
                                                @endif
                                            @elseif(empty($dataTypeContent->{$row->field}))
                                                <div class="detail-empty">
                                                    @include('voyager::multilingual.input-hidden-bread-read')
                                                    <span>{{ $dataTypeContent->{$row->field} }}</span>
                                                </div>
                                            @else
                                                @if($row->field == "value" && $dataType->getTranslatedAttribute('name') == 'customer_margin_fees')
                                                    <div class="detail">
                                                        @include('voyager::multilingual.input-hidden-bread-read')
                                                        <span>{{ $dataTypeContent->{$row->field} }}%</span>
                                                    </div>
                                                @else
                                                    <div class="detail">
                                                        @include('voyager::multilingual.input-hidden-bread-read')
                                                        <span>{{ $dataTypeContent->{$row->field} }}</span>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Single delete modal --}}
    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="{{ __('voyager::generic.close') }}"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"> {{ __('voyager::generic.delete_question') }} {{ strtolower($dataType->getTranslatedAttribute('display_name_singular')) }}?</h4>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('voyager.'.$dataType->slug.'.index') }}" id="delete_form" method="POST">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger pull-right delete-confirm"
                               value="{{ __('voyager::generic.delete_confirm') }}">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-bs-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@stop

@section('javascript')
    @if ($isModelTranslatable)
        <script>
            $(document).ready(function () {
                $('.side-body').multilingual();
            });
        </script>
    @endif
    <script>
        var deleteFormAction;
        $('.delete').on('click', function (e) {
            var form = $('#delete_form')[0];

            if (!deleteFormAction) {
                // Save form action initial value
                deleteFormAction = form.action;
            }

            form.action = deleteFormAction.match(/\/[0-9]+$/)
                ? deleteFormAction.replace(/([0-9]+$)/, $(this).data('id'))
                : deleteFormAction + '/' + $(this).data('id')+'<?php if(request()->name && isset(request()->name)){ if(isset(request()->merchant_name_id)){echo '?name='.request()->name.'&merchant_name_id='.request()->merchant_name_id;}else{ echo '?name='.request()->name;}} ?>';
        

            $('#delete_modal').modal('show');
        });

    </script>
@stop
