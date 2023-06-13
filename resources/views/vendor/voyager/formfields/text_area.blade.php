@if($row->field == 'service_options' && !is_null($dataTypeContent->getKey())  || $row->field == 'contract' && !is_null($dataTypeContent->getKey()))
@php
    $pretty_json = json_decode($dataTypeContent->{$row->field});
@endphp
  <textarea @if($row->required == 1) required @endif class="form-control" name="{{ $row->field }}" rows="{{ $options->display->rows ?? 8 }}">
{{ old($row->field, json_encode($pretty_json,JSON_PRETTY_PRINT)?? $options->default ?? '') }}
  </textarea>
  @else
  <textarea @if($row->required == 1) required @endif class="form-control" name="{{ $row->field }}" rows="{{ $options->display->rows ?? 5 }}">{{ old($row->field, $dataTypeContent->{$row->field} ?? $options->default ?? '') }}</textarea>
@endif