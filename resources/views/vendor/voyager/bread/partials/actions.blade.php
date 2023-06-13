
@if($data)
    @php
        // need to recreate object because policy might depend on record data
        $class = get_class($action);
        $action = new $class($dataType, $data);
    @endphp
    @can ($action->getPolicy(), $data)
        {{-- 
            // defualt code with button title

            <!-- <a href="{{ $action->getRoute($dataType->name) }}" title="{{ $action->getTitle() }}" {!! $action->convertAttributesToHtml() !!}>
                <i class="{{ $action->getIcon() }}"></i><span class="hidden-xs hidden-sm">{{ $action->getTitle() }}</span>
            </a>  -->
        --}}

        @if(isset($product_item_prices) && $controller == 'Product_Pricing_Grids')
            <a href="{{ $action->getRoute($dataType->name)}}@if($action->getPolicy()=='edit'| $action->getPolicy()=='read' && isset($controller)){{ '?name='.$controller.'/'.$dataTypeContent->id.'&merchant_id='.$dataTypeContent->merchant_id.'&pricing_grid_id='.$dataTypeContent->id}}@if(isset($merchant_name_id)){{ '&merchant_name_id='.$merchant_name_id}} @endif @endif" title="{{ $action->getTitle() }}" {!! $action->convertAttributesToHtml() !!}>
                <i class="{{ $action->getIcon() }}" name="{{ $dataType->name.'/'.$data->{$data->getKeyName()} }}" style="margin:2px;font-size: 12px;"></i>
                <p style="display: inline-block; float: right; margin: auto;">{{ $action->getText() }}</p>
            </a>
        @elseif(isset($contacts)||isset($product_item_prices))
            <a href="{{ $action->getRoute($dataType->name)}}@if($action->getPolicy()=='edit'| $action->getPolicy()=='read' && isset($controller)){{ '?name='.$controller.'/'.$dataTypeContent->id.'&merchant_id='.$dataTypeContent->id}}@if(isset($merchant_name_id)){{ '&merchant_name_id='.$merchant_name_id}} @endif @endif" title="{{ $action->getTitle() }}" {!! $action->convertAttributesToHtml() !!}>
                <i class="{{ $action->getIcon() }}" name="{{ $dataType->name.'/'.$data->{$data->getKeyName()} }}" style="margin:2px;font-size: 12px;"></i>
                <p style="display: inline-block; float: right; margin: auto;">{{ $action->getText() }}</p>
            </a>
        @elseif(isset($payment_method) || isset($users) || isset($ip_white_list)){{--<!--check if point from sublist user and payment_method sublist in integration-->--}}
            <a href="{{ $action->getRoute($dataType->name)}}@if($action->getPolicy()=='edit'| $action->getPolicy()=='read' && isset($controller)){{ '?name='.$controller.'/'.$dataTypeContent->id}} @endif" title="{{ $action->getTitle() }}" {!! $action->convertAttributesToHtml() !!}>
                <i class="{{ $action->getIcon() }}" name="{{ $dataType->name.'/'.$data->{$data->getKeyName()} }}" style="margin:2px;font-size: 12px;"></i>
                <p style="display: inline-block; float: right; margin: auto;">{{ $action->getText() }}</p>
            </a>
        @elseif(isset($reason_child) && $controller == 'Reason'){{--<!--check if point from reasons and payment_method sublist in integration-->--}}
            <a href="{{ $action->getRoute($dataType->name)}}@if($action->getPolicy()=='edit'| $action->getPolicy()=='read' && isset($controller)){{ '?name='.$controller.'&&id='.$dataTypeContent->id}} @endif" title="{{ $action->getTitle() }}" {!! $action->convertAttributesToHtml() !!}>
                <i class="{{ $action->getIcon() }}" name="{{ $dataType->name.'/'.$data->{$data->getKeyName()} }}" style="margin:2px;font-size: 12px;"></i>
                <p style="display: inline-block; float: right; margin: auto;">{{ $action->getText() }}</p>
            </a>
        @else
            <a href="{{ $action->getRoute($dataType->name) }}" title="{{ $action->getTitle() }}" {!! $action->convertAttributesToHtml() !!}>
                <i class="{{ $action->getIcon() }}" style="margin:2px;font-size: 12px;"></i>
                <p style="display: inline-block; float: right; margin: auto;">{{ $action->getText() }}</p>
            </a>
        @endif
        
    @endcan
@elseif (method_exists($action, 'massAction'))
    <form method="post" action="{{ route('voyager.'.$dataType->slug.'.action') }}" style="display:inline">
        {{ csrf_field() }}
        <button type="submit" {!! $action->convertAttributesToHtml() !!}><i class="{{ $action->getIcon() }}"></i>  {{ $action->getTitle() }}</button>
        <input type="hidden" name="action" value="{{ get_class($action) }}">
        <input type="hidden" name="ids" value="" class="selected_ids">
    </form>
@endif
