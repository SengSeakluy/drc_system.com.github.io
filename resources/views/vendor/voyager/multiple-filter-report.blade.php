@section('css')
    <style>
        #filter-column {
            width: 390px;
            margin-left: 93px;
            float: right;
            background-color: #f7f7f7;
            border-radius: 4px;
            outline: 0;
            background-image: linear-gradient( 180deg,#fff 50%,#eee);
            background-repeat: repeat-x;
            border: 1px solid #f1f1f1;
        }
    </style>
@stop
<form method="get" action="reports" class="form-search">
    <fieldset style="margin-bottom: 20px;">
        <legend data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" class="filter-collapse">
            <div class="voyager-sort-desc"></div>    
            Filters
        </legend>
        <div class="collapse in" id="collapseExample">
            <div class="row">
                <div class="col-md-12">
                    <div id="search-table" style="display: inline-block;margin-left: 26px;margin-top: 7px;">
                        <p style="display: inline-block;transform: translateY(2px);margin-right: 20px;">Add Filter: </p>
                        <select class="form-control" id="filter-column" data-select2-id="filter-column" tabindex="-1" aria-hidden="true">
                            <option value="" data-select2-id="2">None</option>
                            @foreach ($dataFieldsType as $dataType)
                                <option value="{{ $dataType->Field }}_block" data-select2-id="{{ $dataType->Field }}">{{ strtoupper(str_replace('_', ' ', $dataType->Field)) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    @foreach ($dataFieldsType as $dataType)
                    <div class="col-md-6 {{ $dataType->Field }}_block" @if (isset($newFilterObject[$dataType->Field])) style="display:block" @else style="display:none" @endif>                                     
                        <div class="form-group  col-md-12 ">
                            <input type="checkbox" class="remove-filter" style="margin-right: 5px" checked="">
                            <label class="control-label" style="color: black;font-weight: 600;" for="{{ $dataType->Field }}">{{ strtoupper(str_replace('_', ' ', $dataType->Field)) }}</label>
                            @if (strpos($dataType->Type, 'datetime') !== false || strpos($dataType->Type, 'timestamp') !== false || strpos($dataType->Type, 'date') !== false)
                                <select name="{{ $dataType->Field }}_type" class="operative_type" data-value="{{ $dataType->Field }}" style="width:85px;margin-left:0px;border: 0px solid;background-color: #00ffff00;">
                                    <option value="=" @if (isset($selectDateExpression[$dataType->Field."_type"]) && $selectDateExpression[$dataType->Field."_type"] == "=") SELECTED @endif >IS</option>
                                    <option value="<=" @if (isset($selectDateExpression[$dataType->Field."_type"]) && $selectDateExpression[$dataType->Field."_type"] == "<=") SELECTED @endif >BEFORE</option>
                                    <option value=">=" @if (isset($selectDateExpression[$dataType->Field."_type"]) && $selectDateExpression[$dataType->Field."_type"] == ">=") SELECTED @endif >AFTER</option>
                                    <option value="BETWEEN" @if (isset($selectDateExpression[$dataType->Field."_type"]) && $selectDateExpression[$dataType->Field."_type"] == "BETWEEN") SELECTED @endif >BETWEEN</option>
                                </select>
                                <div class="{{ $dataType->Field }}">
                                    @if (isset($selectDateExpression[$dataType->Field."_type"]) && $selectDateExpression[$dataType->Field."_type"] == "BETWEEN") 
                                        <input type="date" class="form-control" style="margin-bottom: 15px;" name="{{ $dataType->Field }}"  @if (isset($newFilterObject[$dataType->Field])) value="{{ $newFilterObject[$dataType->Field] }}" @endif>
                                        <label class="control-label" style="color: black;font-weight: 600;" for="{{ $dataType->Field }}">AND</label>
                                        <input type="date" class="form-control" name="{{ $dataType->Field.'_to' }}"  @if (isset($newFilterObject[$dataType->Field."_to"])) value="{{ $newFilterObject[$dataType->Field."_to"] }}" @endif>
                                    @else
                                        <input type="date" class="form-control" name="{{ $dataType->Field }}"  @if (isset($newFilterObject[$dataType->Field])) value="{{ $newFilterObject[$dataType->Field] }}" @endif>   
                                    @endif
                                </div>
                            @else 
                                <input type="text" class="form-control" name="{{ $dataType->Field }}"  @if (isset($newFilterObject[$dataType->Field])) value="{{ $newFilterObject[$dataType->Field] }}" @endif>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div style="margin-left: 0px;">
                <input type="hidden" class="form-control" name="table"  value="{{ $temporaryTable }}">
                <button type="submit" class="btn btn-info apply-filter" style="float: left;clear: left;margin-left: 25px;width: 110px;padding-left: 6px;padding-top: 5px;"><span style="font-size: 15px;"><i class="icon voyager-check" style="position: relative;top: 3px;"></i> Apply</span></button>
                <button type="button" class="btn btn-warning clear-filter" style="float: left;margin-left: 5px;width: 110px;height: 36px;"><span style="font-size: 15px;"><i class="icon voyager-refresh" style="position: relative;top: 2px;"></i> Clear</span></button>
            </div>
        </div>
    </fieldset>
</form>
@section('javascript')
    <script>
        $( document ).ready(function() {
            $(" .operative_type ").change(function(e) {
                let _attribute_name = $(this).attr("data-value");
                let _html = '';
                if ($(this).val() == "BETWEEN") {
                    _html +='<input type="date" class="form-control" style="margin-bottom: 25px;" name="'+_attribute_name+'">';
                    _html +='<label class="control-label" style="color: black;font-weight: 600;" for="">AND</label>';
                    _html +='<input type="date" class="form-control" name="'+_attribute_name+'_to">';
                } else {
                    _html +='<input type="date" class="form-control" name="'+_attribute_name+'">';
                }
                $( "."+_attribute_name ).html(_html);
            });

            // reset filter
            $(" .clear-filter ").click(function(e) {
                $(this).closest(' .form-search ').find("input[type=text], input[type=date], textarea, select").val("");
            });

            // remove filter 
            $(" .remove-filter ").click(function(e) {
                let _parent_div = $(this).parent().parent();
                _parent_div.find("input[type=text], input[type=date], textarea, select").val("");
                _parent_div.css("display","none");
                $(this).prop('checked', true);
            });

            // show filter control
            $(" #filter-column ").change(function(e) {
                let _attribute_value = $(this).val();
                $("select option:contains('"+_attribute_value+"')").attr("disabled","disabled");
                $(" ."+_attribute_value).css("display","block");
            });
        });
    </script>
@stop