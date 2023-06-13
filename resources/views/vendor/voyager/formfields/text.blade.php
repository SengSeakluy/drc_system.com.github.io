@if(!is_null($dataTypeContent->getKey()) && $row->field == 'sku' && $dataType->slug == 'products')
        @php
                $count = app('App\Models\ProductItemPrice')::where('product_sku', $dataTypeContent->sku)->count(); 
        @endphp
        @if($count > 0 && $row->field == 'sku')
        <input type="hidden" name="{{ $row->field }}" value="{{ old($row->field, $dataTypeContent->{$row->field} ?? $options->default ?? '') }}">
        @endif
        <input @if($row->required == 1) required @endif type="text" class="form-control" @if($count <= 0 && $row->field == 'sku') name="{{ $row->field }}" @endif
                placeholder="{{ old($row->field, $options->placeholder ?? $row->getTranslatedAttribute('display_name')) }}"
                {!! isBreadSlugAutoGenerator($options) !!}
                value="{{ old($row->field, $dataTypeContent->{$row->field} ?? $options->default ?? '') }}" @if($count > 0 && $row->field == 'sku') disabled @endif>
@else
        <input @if($row->required == 1) required @endif type="text" class="form-control" name="{{ $row->field }}"
                placeholder="{{ old($row->field, $options->placeholder ?? $row->getTranslatedAttribute('display_name')) }}"
                {!! isBreadSlugAutoGenerator($options) !!}
                value="{{ old($row->field, $dataTypeContent->{$row->field} ?? $options->default ?? '') }}">
@endif