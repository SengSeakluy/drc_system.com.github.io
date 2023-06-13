<form method="get" class="form-search" >
    <fieldset style="margin-bottom: 20px;">
        <legend data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" class="filter-collapse">
            <div class="voyager-sort-desc" style="transform: rotate(270deg);"></div>    
            Filters
        </legend>    

        <div class="collapse" id="collapseExample">
            <div style="display: inline-block;">
                <div id="search-table" style="display: inline-block;margin-left: 26px;margin-top: 7px;">
                    <p style="display: inline-block;transform: translateY(2px);margin-right: 20px;">Add Filter: </p>
                    <select id="filter-column" style="width: 300px;margin-left: 93px;">
                        <option value="">None</option>
                    </select>
                </div>
                <table id="filters-table">
                    <tbody>
                    </tbody>
                </table>
                <input type="hidden" id="data-filter" name="data_filter">
                <div style="margin-left: 0px;">
                    <button type="submit" class="btn btn-info apply-filter" style="float: left;clear: left;margin-left: 25px;width: 110px;padding-left: 6px;padding-top: 5px;"><span style="font-size: 15px;"><i class="icon voyager-check" style="position: relative;top: 3px;"></i> Apply</span></button>
                    <button type="button" class="btn btn-warning clear-filter" style="float: left;margin-left: 5px;width: 110px;height: 36px;"><span style="font-size: 15px;"><i class="icon voyager-refresh" style="position: relative;top: 2px;"></i> Clear</span></button>
                </div>
            </div>
            @if(isset($sub_total) && isset($grand_total) && isset($crc))
            <div style="float: right;margin-top:171px">
                <label style="color:#337ab7;">Sub Total:</label>
                <span style="color: #337ab7;font-weight: bold;">{{$sub_total}} {{$crc}}</span>
                <span style="color: #337ab7;">/</span>
                <label style="color: #337ab7;">Grand Total:</label>
                <span style="color: #337ab7;font-weight: bold;">{{$grand_total}} {{$crc}}</span>
            </div>
            @endif
        </div>
    
    </fieldset>
</form>