@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' '.$dataType->getTranslatedAttribute('display_name_plural'))

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="{{ $dataType->icon }}"></i> {{ $dataType->getTranslatedAttribute('display_name_plural') }}
        </h1>
        @can('add', app($dataType->model_name))
            <a href="{{ route('voyager.'.$dataType->slug.'.create') }}" class="btn btn-success btn-add-new">
                <i class="voyager-plus"></i> <span>{{ __('voyager::generic.add_new') }}</span>
            </a>
        @endcan
        @can('delete', app($dataType->model_name))
            @if( !$showSoftDeleted )   @include('voyager::partials.bulk-delete') @endif
        @endcan  
        @can('edit', app($dataType->model_name))
            @if(isset($dataType->order_column) && isset($dataType->order_display_column))
                <a href="{{ route('voyager.'.$dataType->slug.'.order') }}" class="btn btn-primary btn-add-new">
                    <i class="voyager-list"></i> <span>{{ __('voyager::bread.order') }}</span>
                </a>
            @endif
        @endcan
        @can('delete', app($dataType->model_name))
            @if($usesSoftDeletes)
                <input type="checkbox" @if ($showSoftDeleted) checked @endif id="show_soft_deletes" data-toggle="toggle" data-on="{{ __('voyager::bread.soft_deletes_off') }}" data-off="{{ __('voyager::bread.soft_deletes_on') }}">
            @endif
        @endcan
        @foreach($actions as $action)
            @if (method_exists($action, 'massAction'))
                @include('voyager::bread.partials.actions', ['action' => $action, 'data' => null])
            @endif
        @endforeach
        @include('voyager::multilingual.language-selector')
        @if (!$dataTypeContent->isEmpty())
        <div class="pull-right" style="margin-top:35px;">
            <label style="font-weight: bold;">Export To:</label>
            <form method="GET" action="{{route('export.csv', $dataType->slug)}}" style="display: inline-block;" >
                <input type="hidden" id="data_export_csv" name="data_export_filter">
                <button type="submit" style="font-weight: bold;" class="btn btn-default export-csv"><img src="https://freepngimg.com/thumb/microsoft/26716-4-excel-transparent.png" width="20" /> <span>CSV</span></button>
            </form>
            <form method="GET" action="{{route('export.pdf', $dataType->slug)}}" style="display: inline-block;" >
                <input type="hidden" id="data_export_pdf" name="data_export_filter">
                <button type="submit" style="font-weight: bold;" class="btn btn-default export-pdf"><img src="https://user-images.githubusercontent.com/46777604/52937599-ddf15080-335f-11e9-8fc7-57e4fcda6eb7.png" width="20" /> <span>PDF</span></button>
            </form>
        </div>
        @endif
    </div>
@stop

@section('content')

    <div class="page-content browse container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div class="col-md-12">
            @if ($dataTypeContent->isEmpty())
                @php
                    $result[] = [];
                @endphp
                <div id="dataTable" class="hidden"></div>
                <div class="empty-list-container">
                    <div class="py-5">
                        <img class="mx-auto d-block pt-5" src="/images/empty-list/group-5.png" alt="">
                        <h3 class="mt-4 text-center">Add your {{ $dataType->getTranslatedAttribute('name') }} here</h3>
                        <p class="mt-4 text-center" >check the status of your {{ $dataType->getTranslatedAttribute('name') }} and review your {{ $dataType->getTranslatedAttribute('name') }} history</p>
                        <div class="d-flex justify-content-center" style="padding-bottom: 100px;">   
                            <span class="dropdown-container mx-auto" style="height: 60px; border:none;">
                                <button id="dropdownAdd" class="dropdown mb-0">
                                    <span class="text-center"><i class="fa-solid fa-ellipsis-vertical"></i></span>
                                </button>
                                <div id="dropdownMenuAdd" class="hidden" data-hidden="true">
                                    @can('add', app($dataType->model_name))
                                    <div>
                                        <a href="{{ route('voyager.'.$dataType->slug.'.create') }} " class="text-decoration-none">
                                            <span>Add {{ $dataType->getTranslatedAttribute('name') }}</span> 
                                        </a>
                                    </div>
                                    @endcan
                                    {{-- 
                                    <div><span>Import from CSV</span></div>
                                    <div><span>Import from API</span></div>
                                    --}}
                                </div>
                            </span>
                        </div> 
                    </div>
                </div>
            @else    
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        @if ($isServerSide)
                            @include('vendor.voyager.formfields.form_filter')
                        @endif
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-hover">
                                <thead>
                                    <tr>
                                        @if($showCheckboxColumn && !$showSoftDeleted)
                                            <th class="dt-not-orderable">
                                                <input type="checkbox" class="select_all">
                                            </th>
                                        @endif
                                        @foreach($dataType->browseRows->toArray() as $key => $row)
                                            @php
                                            if($row['field'] != 'deleted_at' || intval(request()->showSoftDeleted) == 1) {
                                                $result[] = [
                                                            "id" => $key,
                                                            "text" => $row['display_name'],
                                                            "type" => $row['type'],
                                                            "field" => $row['field'],
                                                            "details" => $row['details']
                                                        ];
                                            }
                                            @endphp
                                        @endforeach
                                        <th>Nº</th>
                                        @foreach($dataType->browseRows as $row)
                                        <th  @if( $row->field == 'deleted_at' && !$showSoftDeleted) style="display:none"   @endif>
                                            @if ($isServerSide && $row->type !== 'relationship')
                                                <a href="{{ $row->sortByUrl($orderBy, $sortOrder) }}">
                                            @endif
                                                <div style="width: max-content;"> {{ $row->getTranslatedAttribute('display_name') }} </div>
                                            @if ($isServerSide)
                                                @if ($row->isCurrentSortField($orderBy))
                                                    @if ($sortOrder == 'asc')
                                                        <i class="voyager-angle-up pull-right"></i>
                                                    @else
                                                        <i class="voyager-angle-down pull-right"></i>
                                                    @endif
                                                @endif
                                                </a>
                                            @endif
                                        </th>
                                        @endforeach
                                        <th class="actions text-right dt-not-orderable">{{ __('voyager::generic.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dataTypeContent as $key => $data)
                                    <tr>
                                        @if($showCheckboxColumn && !$showSoftDeleted)
                                            <td>
                                                <input type="checkbox" name="row_id" id="checkbox_{{ $data->getKey() }}" value="{{ $data->getKey() }}">
                                            </td>
                                        @endif
                                        <td> {{($key + 1)}} </td>
                                        @foreach($dataType->browseRows as $row)
                                            @php
                                            if ($data->{$row->field.'_browse'}) {
                                                $data->{$row->field} = $data->{$row->field.'_browse'};
                                            }
                                            @endphp
                                            <td  @if( $row->field == 'deleted_at' && !$showSoftDeleted) style="display:none"   @endif>
                                                @if (isset($row->details->view))
                                                    @include($row->details->view, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $data->{$row->field}, 'action' => 'browse', 'view' => 'browse', 'options' => $row->details])
                                                @elseif($row->type == 'image')
                                                    <img src="@if( !filter_var($data->{$row->field}, FILTER_VALIDATE_URL)){{ Voyager::image( $data->{$row->field} ) }}@else{{ $data->{$row->field} }}@endif" style="width:100px">
                                                @elseif($row->type == 'relationship')
                                                    @include('voyager::formfields.relationship', ['view' => 'browse','options' => $row->details])
                                                @elseif($row->type == 'select_multiple')
                                                    @if(property_exists($row->details, 'relationship'))

                                                        @foreach($data->{$row->field} as $item)
                                                            {{ $item->{$row->field} }}
                                                        @endforeach

                                                    @elseif(property_exists($row->details, 'options'))
                                                        @if (!empty(json_decode($data->{$row->field})))
                                                            @foreach(json_decode($data->{$row->field}) as $item)
                                                                @if (@$row->details->options->{$item})
                                                                    {{ $row->details->options->{$item} . (!$loop->last ? ', ' : '') }}
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            {{ __('voyager::generic.none') }}
                                                        @endif
                                                    @endif

                                                    @elseif($row->type == 'multiple_checkbox' && property_exists($row->details, 'options'))

                                                        @php
                                                            $data_multiple = json_decode($data->{$row->field});
                                                        @endphp
                                                        @if ($data_multiple != null)
                                                            @foreach(json_decode($data->{$row->field}) as $item)
                                                                @if (@$row->details->options->{$item})
                                                                    {{ $row->details->options->{$item} . (!$loop->last ? ', ' : '') }}
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            {{ __('voyager::generic.none') }}
                                                        @endif

                                                @elseif(($row->type == 'select_dropdown' || $row->type == 'radio_btn') && property_exists($row->details, 'options'))
                                                    @if($row->field=='status')
                                                        <span @if( $data->{$row->field} === 'SUCCESS' || $data->{$row->field} === 1) style="background-color:{{ '#2ecc71' }} @elseif( $data->{$row->field} === 'WAITING' ) style="background-color:{{'#FFCC00'}} @elseif( $data->{$row->field} === 'CLOSED' || $data->{$row->field} === 'FAILED' || $data->{$row->field} === 0 ) style="background-color:{{'#fa2a00'}} @endif;text-align: center;width:70px;padding:5px 15px;border-radius:10px;color:white;font-size:14px;">{!! $row->details->options->{$data->{$row->field}} ?? '' !!}</span>
                                                    @else 
                                                        {!! $row->details->options->{$data->{$row->field}} ?? '' !!}
                                                    @endif

                                                @elseif($row->type == 'date' || $row->type == 'timestamp')
                                                    @if ( property_exists($row->details, 'format') && !is_null($data->{$row->field}) )
                                                        {{ \Carbon\Carbon::parse($data->{$row->field})->formatLocalized($row->details->format) }}
                                                    @else       
                                                        @if (isset($data->{$row->field}))                                                                                                    
                                                            {{ \Carbon\Carbon::parse($data->{$row->field})->setTimezone(config('app.client_timezone'))->toDateTimeString() }}
                                                        @endif
                                                    @endif
                                                @elseif($row->type == 'checkbox')
                                                    @if ($row->field == 'is_seller')
                                                        @if ($data->{$row->field} == 1)
                                                            <span class="label label-primary p-2">Yes</span>
                                                        @else
                                                            <span class="label label-danger p-2">No</span>
                                                        @endif
                                                    @else
                                                        @if(property_exists($row->details, 'on') && property_exists($row->details, 'off'))
                                                            @if($data->{$row->field})
                                                                <span class="label label-info">{{ $row->details->on }}</span>
                                                            @else
                                                                <span class="label label-primary">{{ $row->details->off }}</span>
                                                            @endif
                                                        @else
                                                        {{ $data->{$row->field} }}
                                                        @endif
                                                    @endif
                                                @elseif($row->type == 'color')
                                                    <span class="badge badge-lg" style="background-color: {{ $data->{$row->field} }}">{{ $data->{$row->field} }}</span>
                                                @elseif($row->type == 'text')
                                                    @include('voyager::multilingual.input-hidden-bread-browse')
                                                    @if($row->field == 'status')
                                                        <div @if( $data->{$row->field} == 'SUCCESS' ) style="background-color:{{ '#2ecc71' }} @elseif( $data->{$row->field} == 'WAITING' ) style="background-color:{{'#FFCC00'}} @elseif( $data->{$row->field} == 'CLOSED' || $data->{$row->field} == 'FAILED' ) style="background-color:{{'#fa2a00'}} @endif;text-align: center;width:70px;padding:5px 15px;border-radius:10px;color:white;font-size:14px;">{{ mb_strlen( $data->{$row->field} ) > 200 ? mb_substr($data->{$row->field}, 0, 200) . ' ...' : $data->{$row->field} }}</div>
                                                    @else
                                                        @if($row->field == "value" && $dataType->getTranslatedAttribute('name') == 'customer_margin_fees')
                                                            <div>
                                                                {{ mb_strlen( $data->{$row->field} ) > 200 ? mb_substr($data->{$row->field}, 0, 200) . ' ...' : $data->{$row->field} }}%
                                                            </div>
                                                        @else    
                                                            <div>
                                                                {{ mb_strlen( $data->{$row->field} ) > 200 ? mb_substr($data->{$row->field}, 0, 200) . ' ...' : $data->{$row->field} }}
                                                            </div>
                                                        @endif
                                                    @endif
                                                @elseif($row->type == 'text_area')
                                                    @include('voyager::multilingual.input-hidden-bread-browse')
                                                    <div>{{ mb_strlen( $data->{$row->field} ) > 200 ? mb_substr($data->{$row->field}, 0, 200) . ' ...' : $data->{$row->field} }}</div>
                                                @elseif($row->type == 'file' && !empty($data->{$row->field}) )
                                                    @include('voyager::multilingual.input-hidden-bread-browse')
                                                    @if(json_decode($data->{$row->field}) !== null)
                                                        @foreach(json_decode($data->{$row->field}) as $file)
                                                            <a href="{{ Storage::disk(config('voyager.storage.disk'))->url($file->download_link) ?: '' }}" target="_blank">
                                                                {{ $file->original_name ?: '' }}
                                                            </a>
                                                            <br/>
                                                        @endforeach
                                                    @else
                                                        <a href="{{ Storage::disk(config('voyager.storage.disk'))->url($data->{$row->field}) }}" target="_blank">
                                                            Download
                                                        </a>
                                                    @endif
                                                @elseif($row->type == 'rich_text_box')
                                                    @include('voyager::multilingual.input-hidden-bread-browse')
                                                    <div>{{ mb_strlen( strip_tags($data->{$row->field}, '<b><i><u>') ) > 200 ? mb_substr(strip_tags($data->{$row->field}, '<b><i><u>'), 0, 200) . ' ...' : strip_tags($data->{$row->field}, '<b><i><u>') }}</div>
                                                @elseif($row->type == 'coordinates')
                                                    @include('voyager::partials.coordinates-static-image')
                                                @elseif($row->type == 'multiple_images')
                                                    @php $images = json_decode($data->{$row->field}); @endphp
                                                    @if($images)
                                                        @php $images = array_slice($images, 0, 3); @endphp
                                                        @foreach($images as $image)
                                                            <img src="@if( !filter_var($image, FILTER_VALIDATE_URL)){{ Voyager::image( $image ) }}@else{{ $image }}@endif" style="width:50px">
                                                        @endforeach
                                                    @endif
                                                @elseif($row->type == 'media_picker')
                                                    @php
                                                        if (is_array($data->{$row->field})) {
                                                            $files = $data->{$row->field};
                                                        } else {
                                                            $files = json_decode($data->{$row->field});
                                                        }
                                                    @endphp
                                                    @if ($files)
                                                        @if (property_exists($row->details, 'show_as_images') && $row->details->show_as_images)
                                                            @foreach (array_slice($files, 0, 3) as $file)
                                                            <img src="@if( !filter_var($file, FILTER_VALIDATE_URL)){{ Voyager::image( $file ) }}@else{{ $file }}@endif" style="width:50px">
                                                            @endforeach
                                                        @else
                                                            <ul>
                                                            @foreach (array_slice($files, 0, 3) as $file)
                                                                <li>{{ $file }}</li>
                                                            @endforeach
                                                            </ul>
                                                        @endif
                                                        @if (count($files) > 3)
                                                            {{ __('voyager::media.files_more', ['count' => (count($files) - 3)]) }}
                                                        @endif
                                                    @elseif (is_array($files) && count($files) == 0)
                                                        {{ trans_choice('voyager::media.files', 0) }}
                                                    @elseif ($data->{$row->field} != '')
                                                        @if (property_exists($row->details, 'show_as_images') && $row->details->show_as_images)
                                                            <img src="@if( !filter_var($data->{$row->field}, FILTER_VALIDATE_URL)){{ Voyager::image( $data->{$row->field} ) }}@else{{ $data->{$row->field} }}@endif" style="width:50px">
                                                        @else
                                                            {{ $data->{$row->field} }}
                                                        @endif
                                                    @else
                                                        {{ trans_choice('voyager::media.files', 0) }}
                                                    @endif
                                                @else
                                                    @include('voyager::multilingual.input-hidden-bread-browse')
                                                    <span>{{ $data->{$row->field} }}</span>
                                                @endif
                                            </td>
                                        @endforeach
                                        <td class="no-sort no-click bread-actions">
                                            @if($showSoftDeleted)
                                                @foreach($actions as $action)
                                                    @if(get_class($action)=="App\Http\Controllers\Admin\Actions\RestoreAction")                                                    
                                                        @include('voyager::bread.partials.actions', ['action' => $action])
                                                    @endif
                                                @endforeach
                                            @else
                                                @foreach($actions as $action) 
                                                    @if (!method_exists($action, 'massAction'))
                                                        @include('voyager::bread.partials.actions', ['action' => $action])
                                                    @endif
                                                @endforeach
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if ($isServerSide)
                            <div class="pull-left">
                                <div role="status" class="show-res" aria-live="polite">{{ trans_choice(
                                    'voyager::generic.showing_entries', $dataTypeContent->total(), [
                                        'from' => $dataTypeContent->firstItem(),
                                        'to' => $dataTypeContent->lastItem(),
                                        'all' => $dataTypeContent->total()
                                    ]) }}</div>
                            </div>
                            <div class="pull-right">
                                {{ $dataTypeContent->appends([
                                    's' => $search->value,
                                    'filter' => $search->filter,
                                    'key' => $search->key,
                                    'order_by' => $orderBy,
                                    'sort_order' => $sortOrder,
                                    'showSoftDeleted' => $showSoftDeleted,
                                ])->links('vendor.pagination.custom') }}
                            </div>
                        @endif
                    </div>
                </div>
            @endif 
            </div>
        </div>
    </div>

    {{-- Single delete modal --}}
    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog"  style="max-width: 40%;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="{{ __('voyager::generic.close') }}"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{ __('voyager::generic.delete_question') }} {{ strtolower($dataType->getTranslatedAttribute('display_name_singular')) }}?</h4>
                </div>
                <div class="modal-footer">
                    <form action="#" id="delete_form" method="POST">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger pull-right delete-confirm" value="{{ __('voyager::generic.delete_confirm') }}">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-bs-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@stop

@section('css')
@if(!$dataType->server_side && config('dashboard.data_tables.responsive'))
    <link rel="stylesheet" href="{{ voyager_asset('lib/css/responsive.dataTables.min.css') }}">
@endif
@stop

@include('vendor.voyager.multiple-filter')
