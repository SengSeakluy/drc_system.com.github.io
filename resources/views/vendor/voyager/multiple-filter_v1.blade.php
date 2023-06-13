@section('javascript')
    <!-- DataTables -->
    @if(!$dataType->server_side && config('dashboard.data_tables.responsive'))
        <script src="{{ voyager_asset('lib/js/dataTables.responsive.min.js') }}"></script>
    @endif
    <script>
        $(document).ready(function () {
            @if (!$dataType->server_side)
                var table = $('#dataTable').DataTable({!! json_encode(
                    array_merge([
                        "order" => $orderColumn,
                        "bStateSave" => true,
                        "bPaginate" => true,
                        "bLengthChange" => false,
                        "bFilter" => true,
                        "bInfo" => true,
                        "bAutoWidth"=> false,
                        "language" => __('voyager::datatable'),
                        "columnDefs" => [
                            ['targets' => 'dt-not-orderable', 'searchable' =>  false, 'orderable' => false],
                        ],
                    ],
                    config('voyager.dashboard.data_tables', []))
                , true) !!});
            @else
                $('#search-input select').select2({
                    minimumResultsForSearch: Infinity
                });
            @endif

            @if ($isModelTranslatable)
                $('.side-body').multilingual();
                //Reinitialise the multilingual features when they change tab
                $('#dataTable').on('draw.dt', function(){
                    $('.side-body').data('multilingual').init();
                })
            @endif
            $('.select_all').on('click', function(e) {
                $('input[name="row_id"]').prop('checked', $(this).prop('checked')).trigger('change');
            });

            // ----------------------------------------------------------------------------
            
            let column_names = <?php echo json_encode($result) ?? null; ?>;
            // for note if we are in order or transition page
            
            let page_name = <?php echo '"'.$dataType->slug.'"' ?? '' ?>;
            
            var first_column_id = (+column_names[0].id);
            // if(page_name == 'transactions' || page_name == 'orders') {
            //     first_column_id = (+column_names[0].id + 1)
            // }
            // for remember after reload page
            let remember_filters = {
                "filters_table": [{
                    id: first_column_id,
                    value: "",
                    text: column_names[0].text,
                    type: column_names[0].type,
                    field: column_names[0].field,
                    details: column_names[0].details,
                    operator: 'Is',
                }]
            }
            let submit_filter_data = JSON.parse(JSON.stringify(remember_filters));
            let html = '';
            for(let col_n = 0; col_n < column_names.length; col_n++) {
                html+=`
                    <div class="dropdown filter-box" style="padding: 1rem;">
                        <button class="btn cus-btn dropdown-toggle fw-bold" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside" data-bs-offset="10,10">${column_names[col_n].text}</button>
                        <ul class="dropdown-menu-${column_names[col_n].id} dropdown-menu filter-form" aria-labelledby="dropdownMenuButton1" style="border-radius: 1rem; padding:1rem; "></ul>
                    </div>
                `;
            }
            $('#filter-content').empty().html(html);
            if(!localStorage.getItem(`remember_filters/${page_name}`) || JSON.parse(localStorage.getItem(`remember_filters/${page_name}`)).filters_table.length == 1) {
                column_names.forEach((item, index) => {
                    if(item.id != remember_filters.filters_table[0].id) {
                        if(item.type != 'timestamp' && item.type != 'date') {
                            remember_filters.filters_table.push({
                                id: +item.id,
                                value: '',
                                text: item.text,
                                type: item.type,
                                field: item.field,
                                details: item.details,
                                operator: 'Is',
                            });
                        } else {
                            remember_filters.filters_table.push({
                                id: +item.id,
                                value_start: '',
                                value_end: '',
                                text: item.text,
                                type: item.type,
                                field: item.field,
                                details: item.details,
                                operator: 'Is',
                            });
                        }
                    }
                    localStorage.setItem(`remember_filters/${page_name}`, JSON.stringify(remember_filters));
                });
            }
            for (let [key, value] of Object.entries(localStorage)) {
                if(key.split('/')[0] == 'remember_filters' && key.split('/')[1] != page_name) {
                    localStorage.clear();
                    column_names.forEach((item, index) => {
                        if(item.id != remember_filters.filters_table[index].id) {
                            if(item.type != 'timestamp' && item.type != 'date') {
                                remember_filters.filters_table.push({
                                    id: +item.id,
                                    value: '',
                                    text: item.text,
                                    type: item.type,
                                    field: item.field,
                                    details: item.details,
                                    operator: 'Is',
                                });
                            } else {
                                remember_filters.filters_table.push({
                                    id: +item.id,
                                    value_start: '',
                                    value_end: '',
                                    text: item.text,
                                    type: item.type,
                                    field: item.field,
                                    details: item.details,
                                    operator: 'Is',
                                });
                            }
                        }
                        localStorage.setItem(`remember_filters/${page_name}`, JSON.stringify(remember_filters));
                    });
                }
            }

            if(localStorage.getItem(`remember_filters/${page_name}`)) {
                remember_filters = JSON.parse(localStorage.getItem(`remember_filters/${page_name}`));
                submit_filter_data = JSON.parse(JSON.stringify(remember_filters));
                if(remember_filters.filters_table.length == 0) {
                    remember_filters.filters_table.push({
                        id: first_column_id,
                        value: "",
                        text: column_names[0].text,
                        type: column_names[0].type,
                        field: column_names[0].field,
                        details: column_names[0].details,
                        operator: 'Is',
                    });
                }
                var localStorage_filter = remember_filters.filters_table;
                for(let super_key in localStorage_filter) {
                    var data = localStorage_filter[super_key];
                    if(data.type == 'timestamp' || data.type == 'date') {
                        $(`.dropdown-menu-${data.id}`).empty().append(`
                            <li class="filter">
                                <li class="operator">
                                    <select id="table-operator${+data.id}" data-type="${data.type}" style="width: 300px;">
                                        <option value="Is">Is</option>
                                        <option value="Before">Before</option>
                                        <option value="After">After</option>
                                        <option value="Between">Between</option>
                                    </select>
                                </li>
                                <div class="values" values-number="${+data.id}">
                                    <div class="from-date" style="display:inline-block;">
                                        <label>From Date: </label>
                                        <input type="date" class="datepicker_start_date" data-number="${+data.id}" data-type="${data.type}" style="z-index: 10000;width: 200px;" />
                                        <span data-number="${+data.id}"></span>
                                    </div>
                                    <div class="to-date" style="display:none;margin-left: 20px;">
                                        <label>To Date: </label>
                                        <input type="date" class="datepicker_end_date" data-number="${+data.id}" data-type="${data.type}" style="z-index: 10000;width: 200px;" />
                                        <span data-number="${+data.id}"></span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-6 my-0">
                                        <button class="btn btn-primary btn-xs w-100 apply-filter">Ok</button>
                                    </div>
                                    <div class="col-6 my-0">
                                        <button class="btn btn-default btn-xs w-100 reset" data-id="${+data.id}" data-type="${data.type}">Reset</button>
                                    </div>
                                </div>
                            </li>
                        `);

                        if(data.operator) {
                            $(`[id^=table-operator] option[value="${data.operator}"]`).attr('selected', true);
                            if(data.operator == 'Between') {
                                $('.to-date').css({'display': 'inline-block'});
                            }
                        }
                        $(`#table-operator${+data.id}`).select2({
                            theme: "classic",
                            width: 'resolve',
                            dropdownAutoWidth : true,
                            minimumResultsForSearch: -1,
                        });
                        $(".datepicker_start_date").change(function (e) {
                            var date = e.target.value;
                            minDateFilter = new Date(date).getTime();
                            saved_minDateFilter = {minDateFilter: minDateFilter, date: date};
                            // update remember_filters for remember when reload
                            var foundIndex = remember_filters.filters_table.findIndex(x => x.id == $(this).attr('data-number'));
                            remember_filters.filters_table[foundIndex].value_start = date;
                            localStorage.setItem(`remember_filters/${page_name}`, JSON.stringify(remember_filters));
                        });

                        $(".datepicker_end_date").change(function (e) {
                            var date = e.target.value;
                            maxDateFilter = new Date(date).getTime();
                            saved_maxDateFilter = {maxDateFilter: maxDateFilter, date: date};
                            if(isValidDate(this.value) || this.value == '') {
                                // update remember_filters for remember when reload
                                var foundIndex = remember_filters.filters_table.findIndex(x => x.id == $(this).attr('data-number'));
                                remember_filters.filters_table[foundIndex].value_end = date;
                                localStorage.setItem(`remember_filters/${page_name}`, JSON.stringify(remember_filters));
                                // localStorage.setItem(`saved_maxDateFilter/${page_name}`, JSON.stringify(saved_maxDateFilter));
                            }
                        }); 
            
                        $('[id^=table-operator]').each(function() {
                            $(this).on('select2:select',(e) => {
                                var data = e.params.data;
                                var id = ($(e.target).attr('id'));
                                var field_id = id.substr(14);
                                var foundIndex = remember_filters.filters_table.findIndex(x => x.id == field_id);
                                remember_filters.filters_table[foundIndex].operator = data.id;
                                if(data.id == 'Between') {
                                    $(`[values-number="${field_id}"]`).children('.to-date').css({'display': 'inline-block'});
                                } else {
                                    $(`[values-number="${field_id}"]`).children('.to-date').css({'display': 'none'});
                                }
                                localStorage.setItem(`remember_filters/${page_name}`, JSON.stringify(remember_filters));
                            });
                        });
                        $(`.datepicker_end_date`).val(data.value_end);
                        $(".datepicker_start_date").val(data.value_start);
                        // $('.ui-datepicker-current-day').click();
                        // $('.ui-datepicker-current-day').click();
                    } else if(data.type == "select_dropdown"){
                        $(`.dropdown-menu-${+data.id}`).empty().append(`
                            <div class="values" values-number="${+data.id}">
                                <div>
                                    <select id="table-filter${+data.id}" data-type="${data.type}" style="width: 300px;">
                                        <option value="">None</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-6 my-0">
                                    <button class="btn btn-primary btn-xs w-100 apply-filter">Ok</button>
                                </div>
                                <div class="col-6 my-0">
                                    <button class="btn btn-default btn-xs w-100 reset" data-id="${+data.id}" data-type="${data.type}">Reset</button>
                                </div>
                            </div>
                        `);

                        try {
                            var selectedData = Object.values(data.details.options);
                            var column_to_filter = (+data.id);
                            @if(intval(request()->showSoftDeleted) == 1 || $dataType->slug == 'transactions' || $dataType->slug == 'orders')
                            column_to_filter = (+data.id - 1);
                            @endif
                            
                            //remove all double option
                            selectedData = [...new Set(selectedData)];
                            
                            (function() { // don't leak
                                var elm = document.getElementById(`table-filter${+data.id}`), // get the select
                                    df = document.createDocumentFragment(); // create a document fragment to hold the options while we create them
                                for (let key in selectedData) {
                                    var option = document.createElement('option'); // create the option element
                                    var text = selectedData[key].replace(/^<div[^>]*>|^<span[^>]*>|^<a[^>]*>|<\/span>|<\/a>|<\/div>$/g, '');
                                    if(data.text == 'Status' && text == 'Active') {
                                        option.value = 1;
                                    } else if(data.text == 'Status' && text == 'Inactive') {
                                        option.value = 0;
                                    } else {
                                        option.value = text; // set the value property
                                    }
                                    if(option.value == data.value) {
                                        option.setAttribute('selected', true);
                                    }
                                    option.appendChild(document.createTextNode(text)); // set the textContent in a safe way.
                                    df.appendChild(option); // append the option to the document fragment
                                }
                                elm.appendChild(df); // append the document fragment to the DOM. this is the better way rather than setting innerHTML a bunch of times (or even once with a long string)
                            }());
                            $(`#table-filter${+data.id}`).select2({
                                theme: "classic",
                                width: 'resolve',
                                data: data,
                                dropdownAutoWidth : true
                            });
                        } catch (err) {
                            $(`#table-filter${+data.id}`).select2({
                                theme: "classic",
                                width: 'resolve',
                                data: [],
                                dropdownAutoWidth : true
                            });
                        }
                    } else {
                        $(`.dropdown-menu-${data.id}`).empty().append(`
                            <div class="values" values-number="${data.id}">
                                <div>
                                    <input id="table-filter${+data.id}" data-type="${data.type}" value="${data.value}" class="form-control me-2" style="border-radius: 1rem; height: inherit; min-width: 15rem;" type="search" placeholder="Search" aria-label="Search">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-6 my-0">
                                    <button class="btn btn-primary btn-xs w-100 apply-filter">Ok</button>
                                </div>
                                <div class="col-6 my-0">
                                    <button class="btn btn-default btn-xs w-100 reset" data-id="${+data.id}" data-type="${data.type}">Reset</button>
                                </div>
                            </div>
                        `);
                    }
                    //disable select which column to filter
                    $('#filter-column').find(`option[value="${+data.id - 1}"]`).prop({ disabled: true });
                }
            } else {
                for(let index = 0; index<column_names.length; index++) {
                    if(column_names[index].type == 'relationship' || column_names[index].type == 'text') {
                        $(`.dropdown-menu-${index}`).empty().append(`
                            <div class="values" values-number="${+column_names[0].id}">
                                <div>
                                    <input id="table-filter${+column_names[0].id}" value="${column_names[0].value}" class="form-control me-2" data-type="${column_names[index].type}" style="border-radius: 1rem; height: inherit; min-width: 15rem;" type="search" placeholder="Search" aria-label="Search">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-6 my-0">
                                    <button class="btn btn-primary btn-xs w-100 apply-filter">Ok</button>
                                </div>
                                <div class="col-6 my-0">
                                    <button class="btn btn-default btn-xs w-100 reset" data-id="${column_names[0].id}" data-type="${column_names[index].type}">Reset</button>
                                </div>
                            </div>
                        `);
                    } 
                    if(column_names[index].type == "select_dropdown") {
                        $(`.dropdown-menu-${index}`).empty().append(`
                            <div class="values" values-number="${+column_names[0].id}">
                                <div>
                                    <select id="table-filter${+column_names[0].id}" data-type="${column_names[index].type}" style="width: 300px;">
                                        <option value="">None</option>
                                    </select>    
                                </div
                            </div>
                            <div class="row mt-2">
                                <div class="col-6 my-0">
                                    <button class="btn btn-primary btn-xs w-100 apply-filter">Ok</button>
                                </div>
                                <div class="col-6 my-0">
                                    <button class="btn btn-default btn-xs w-100 reset" data-id="${column_names[0].id}" data-type="${column_names[index].type}">Reset</button>
                                </div>
                            </div>
                        `);
                    }
                    if(column_names[index].type == 'timestamp' || column_names[index].type == 'date') {
                        $(`.dropdown-menu-${index}`).empty().append(`
                            <li class="filter">
                                <li class="operator">
                                    <select id="table-operator${+column_names[index].id}" data-type="${column_names[index].type}" style="width: 300px;">
                                        <option value="Is">Is</option>
                                        <option value="Before">Before</option>
                                        <option value="After">After</option>
                                        <option value="Between">Between</option>
                                    </select>
                                </li>
                                <div class="values" values-number="${+data.id}">
                                    <div class="from-date" style="display:inline-block;">
                                        <label>From Date: </label>
                                        <input type="text" class="datepicker_start_date" data-number="${+data.id}" data-type="${data.type}" style="z-index: 10000;width: 200px;" />
                                        <span data-number="${+data.id}"></span>
                                    </div>
                                    <div class="to-date" style="display:none;margin-left: 20px;">
                                        <label>To Date: </label>
                                        <input type="text" class="datepicker_end_date" data-number="${+data.id}" data-type="${data.type}" style="z-index: 10000;width: 200px;" />
                                        <span data-number="${+data.id}"></span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-6 my-0">
                                        <button class="btn btn-primary btn-xs w-100 apply-filter">Ok</button>
                                    </div>
                                    <div class="col-6 my-0">
                                        <button class="btn btn-default btn-xs w-100 reset" data-id="${column_names[0].id}" data-type="${data.type}">Reset</button>
                                    </div>
                                </div>
                            </li>
                        `);
                        $(`#table-operator${+column_names[index].id}`).select2({
                            theme: "classic",
                            width: 'resolve',
                            dropdownAutoWidth : true,
                            minimumResultsForSearch: -1,
                        });
                        $(".datepicker_start_date").change(function (e) {
                            var date = e.target.value;
                            minDateFilter = new Date(date).getTime();
                            saved_minDateFilter = {minDateFilter: minDateFilter, date: date};
                            // update remember_filters for remember when reload
                            var foundIndex = remember_filters.filters_table.findIndex(x => x.id == $(this).attr('data-number'));
                            remember_filters.filters_table[foundIndex].value_start = date;
                            localStorage.setItem(`remember_filters/${page_name}`, JSON.stringify(remember_filters));
                        });

                        $(".datepicker_end_date").change(function (e) {
                            var date = e.target.value;
                            maxDateFilter = new Date(date).getTime();
                            saved_maxDateFilter = {maxDateFilter: maxDateFilter, date: date};
                            if(isValidDate(this.value) || this.value == '') {
                                // update remember_filters for remember when reload
                                var foundIndex = remember_filters.filters_table.findIndex(x => x.id == $(this).attr('data-number'));
                                remember_filters.filters_table[foundIndex].value_end = date;
                                localStorage.setItem(`remember_filters/${page_name}`, JSON.stringify(remember_filters));
                                // localStorage.setItem(`saved_maxDateFilter/${page_name}`, JSON.stringify(saved_maxDateFilter));
                            }
                        }); 
                        
                        $('[id^=table-operator]').each(function() {
                            $(this).on('select2:select',(e) => {
                                var data = e.params.data;

                                var id = ($(e.target).attr('id'));
                                var field_id = id.substr(14);
                                var foundIndex = remember_filters.filters_table.findIndex(x => x.id == field_id);
                                remember_filters.filters_table[foundIndex].operator = data.id;
                                if(data.id == 'Between') {
                                    $(`[values-number="${field_id}"]`).children('.to-date').css({'display': 'inline-block'});
                                } else {
                                    $(`[values-number="${field_id}"]`).children('.to-date').find('input').val('');
                                    $(`[values-number="${field_id}"]`).children('.to-date').css({'display': 'none'});
                                }
                                localStorage.setItem(`remember_filters/${page_name}`, JSON.stringify(remember_filters));
                            });
                        });
                    }
                }
            }

            // click checkbox to remove row of filter
            $(document).on('click', '[class^=remove-filter]', function(e) {
                var class_name = ($(e.target).attr('class'));
                $('#filter-column').val(null).trigger('change');
                //unlock the select of column
                $('#filter-column').find(`option[value=${parseInt(class_name.charAt(class_name.length - 1)) - 1}]`).prop({ disabled: false });
                
                var foundIndex = remember_filters.filters_table.findIndex(x => x.id == class_name.charAt(class_name.length - 1));
                if (foundIndex > -1) {
                    if((remember_filters.filters_table[foundIndex].type == 'timestamp' || remember_filters.filters_table[foundIndex].type == 'date')) {
                        minDateFilter = "";
                        maxDateFilter = "";
                    }
                    remember_filters.filters_table.splice(foundIndex, 1);
                }
                $("[class^=remove-filter]:checkbox:not(:checked)").closest('.filter').remove();
                localStorage.setItem(`remember_filters/${page_name}`, JSON.stringify(remember_filters));
            });

            $('.apply-filter').click(function(e) {
                submit_filter_data = remember_filters;
                var data_filter = JSON.stringify(remember_filters);
                $('#data-filter').val(data_filter);
            });

            $('.reset').click(function(e) {
                e.preventDefault();
                // $(this).closest('.row').find('.values').find('input').val('');
                $(this).parent().parent().prev().find('input').val('');
                var option_value = '';
                var id = ($(this).attr('data-id'));
                var data_type = $(this).attr('data-type');
                // update remember_filters for remember when reload
                var foundIndex = remember_filters.filters_table.findIndex(x => x.id == id.charAt(id.length - 1));
                if(data_type == 'timestamp' || data_type == 'date') {
                    remember_filters.filters_table[foundIndex].value_start = '';
                    remember_filters.filters_table[foundIndex].value_end = '';
                } else if(data_type == 'select_dropdown') {
                    $(this).parent().parent().prev().find('select').val('').trigger('change');
                    remember_filters.filters_table[foundIndex].value = '';
                } else {
                    remember_filters.filters_table[foundIndex].value = option_value;
                }
                localStorage.setItem(`remember_filters/${page_name}`, JSON.stringify(remember_filters));
            });

            $('.clear-filter').click(function() {
                var $first_field = $('#filters-table tr:first-child').find('.field');
                var only_id = $first_field.attr('data-number');
                var row_filter = [];
                if($first_field.attr('data-type') == 'timestamp' || $first_field.attr('data-type') == 'date') {
                    row_filter.push({
                        id: $first_field.attr('data-number'),
                        value_start: '',
                        value_end: '',
                        text: $first_field.attr('data-name'),
                        type: $first_field.attr('data-type'),
                        field: remember_filters.filters_table[0].field,
                        details: remember_filters.filters_table[0].details,
                        operator: 'Is',
                    });
                } else {
                    row_filter.push({
                        id: $first_field.attr('data-number'),
                        value: '',
                        text: $first_field.attr('data-name'),
                        type: $first_field.attr('data-type'),
                        field: remember_filters.filters_table[0].field,
                        details: remember_filters.filters_table[0].details,
                        operator: 'Is',
                    });
                }
                remember_filters.filters_table = row_filter;
                $('#filters-table tr:not(:first-child)').each(function(){
                    var id = $(this).find('.field').attr('data-number');
                    $('#filter-column').find(`option[value=${parseInt(id) - 1}]`).prop({ disabled: false });
                });
                $('#filters-table tr:not(:first-child)').remove();
                
                $('[id^=table-filter]').val('').trigger('change');
                $('input').val('');
                $('[id^=table-operator]').val('Is').trigger('change');
                $('.to-date').css({'display': 'none'});

                localStorage.setItem(`remember_filters/${page_name}`, JSON.stringify(remember_filters));
            });

            //select to filter and trigger search select of Datatable
            $(document).on('keyup', '[id^=table-filter]', function(e) {
                var option_value = $(e.target).val();
                var id = ($(e.target).attr('id'));
                // update remember_filters for remember when reload
                var foundIndex = remember_filters.filters_table.findIndex(x => x.id == id.charAt(id.length - 1));
                remember_filters.filters_table[foundIndex].value = option_value;
                localStorage.setItem(`remember_filters/${page_name}`, JSON.stringify(remember_filters));
            });

            //select to filter and trigger search select of Datatable
            $(document).on('select2:select', '[id^=table-filter]', function(e) {
                var value = e.params.data;
                var id = ($(e.target).attr('id'));
                var option_value = value.id;
                //check if we select None option
                if(value.text == 'None') {
                    option_value = '';
                }
                // update remember_filters for remember when reload
                var foundIndex = remember_filters.filters_table.findIndex(x => x.id == id.charAt(id.length - 1));
                remember_filters.filters_table[foundIndex].value = option_value;
                localStorage.setItem(`remember_filters/${page_name}`, JSON.stringify(remember_filters));
            });


            $('.export-csv').click( function(){
                var data_filter = JSON.stringify(submit_filter_data);
                $('#data_export_csv').val(data_filter);
            })
            $('.export-pdf').click( function(){
                var data_filter = JSON.stringify(submit_filter_data);
                $('#data_export_pdf').val(data_filter);
            })


            // hide search bar of datatable
            $('.dataTables_filter').hide();

            // rotate collapse icon
            let collapse_filter = false;
            $('.filter-collapse').on('click', function() {
                collapse_filter = !collapse_filter;
                if(collapse_filter == true) {
                    $('.voyager-sort-desc').css('transform', 'rotate(270deg)');
                } else {
                    $('.voyager-sort-desc').css('transform', 'rotate(0deg)');
                }
            });

            // check if string is number
            function isNumeric(str) {
                if (typeof str != "string") return false // we only process strings!  
                return !isNaN(str) && // use type coercion to parse the _entirety_ of the string (`parseFloat` alone does not do this)...
                        !isNaN(parseFloat(str)) // ...and ensure strings of whitespace fail
            }
            function formatState (state) {
                state = '';
                return state;
            };

            function isValidDate(dateString) {
                var regEx = /^\d{4}-\d{2}-\d{2}$/;
                if(!dateString.match(regEx)) return false;  // Invalid format
                var d = new Date(dateString);
                var dNum = d.getTime();
                if(!dNum && dNum !== 0) return false; // NaN value, Invalid date
                return d.toISOString().slice(0,10) === dateString;
            }

        });


        var deleteFormAction;
        $('td').on('click', '.delete', function (e) {
            $('#delete_form')[0].action = '{{ route('voyager.'.$dataType->slug.'.destroy', '__id') }}'.replace('__id', $(this).data('id'));
            $('#delete_modal').modal('show');
        });

        @if($usesSoftDeletes)
            @php
                $params = [
                    's' => $search->value,
                    'filter' => $search->filter,
                    'key' => $search->key,
                    'order_by' => $orderBy,
                    'sort_order' => $sortOrder,
                ];
            @endphp
            $(function() {
                $('#show_soft_deletes').change(function() {
                    if ($(this).prop('checked')) {
                        $('#dataTable').before('<a id="redir" href="{{ (route('voyager.'.$dataType->slug.'.index', array_merge($params, ['showSoftDeleted' => 1]), true)) }}"></a>');
                    }else{
                        $('#dataTable').before('<a id="redir" href="{{ (route('voyager.'.$dataType->slug.'.index', array_merge($params, ['showSoftDeleted' => 0]), true)) }}"></a>');
                    }

                    $('#redir')[0].click();
                })
            })
        @endif
        $('input[name="row_id"]').on('change', function () {
            var ids = [];
            $('input[name="row_id"]').each(function() {
                if ($(this).is(':checked')) {
                    ids.push($(this).val());
                }
            });
            $('.selected_ids').val(ids);
        });
        
    </script>
@stop




{{--
@section('javascript')
    <!-- DataTables -->
    @if(!$dataType->server_side && config('dashboard.data_tables.responsive'))
        <script src="{{ voyager_asset('lib/js/dataTables.responsive.min.js') }}"></script>
    @endif
    <script>
        $(document).ready(function () {
            @if (!$dataType->server_side)
                var table = $('#dataTable').DataTable({!! json_encode(
                    array_merge([
                        "order" => $orderColumn,
                        "bStateSave" => true,
                        "bPaginate" => true,
                        "bLengthChange" => false,
                        "bFilter" => true,
                        "bInfo" => true,
                        "bAutoWidth"=> false,
                        "language" => __('voyager::datatable'),
                        "columnDefs" => [
                            ['targets' => 'dt-not-orderable', 'searchable' =>  false, 'orderable' => false],
                        ],
                    ],
                    config('voyager.dashboard.data_tables', []))
                , true) !!});
            @else
                $('#search-input select').select2({
                    minimumResultsForSearch: Infinity
                });
            @endif

            @if ($isModelTranslatable)
                $('.side-body').multilingual();
                //Reinitialise the multilingual features when they change tab
                $('#dataTable').on('draw.dt', function(){
                    $('.side-body').data('multilingual').init();
                })
            @endif
            $('.select_all').on('click', function(e) {
                $('input[name="row_id"]').prop('checked', $(this).prop('checked')).trigger('change');
            });

            // ----------------------------------------------------------------------------
            
            let column_names = <?php echo json_encode($result) ?? null; ?>;
            
            // for note if we are in order or transition page
            
            let page_name = <?php echo '"'.$dataType->slug.'"' ?? '' ?>;
            
            var first_column_id = (+column_names[0].id + 1);
            if(page_name == 'transactions' || page_name == 'orders') {
                first_column_id = (+column_names[0].id + 1)
            }
            // for remember after reload page
            let remember_filters = {
                "filters_table": [{
                    id: first_column_id,
                    value: "",
                    text: column_names[0].text,
                    type: column_names[0].type,
                    field: column_names[0].field,
                    details: column_names[0].details,
                    operator: 'Is',
                }]
            }
            let submit_filter_data = JSON.parse(JSON.stringify(remember_filters));
            $('#filter-column').select2({
                placeholder: 'Filter',
                data: column_names,
                theme: "classic",
                width: 'resolve',
                minimumResultsForSearch: -1,
                dropdownAutoWidth : true,
                templateSelection: formatState
            });

            for (let [key, value] of Object.entries(localStorage)) {
                if(key.split('/')[0] == 'remember_filters' && key.split('/')[1] != page_name) {
                    localStorage.clear();
                }
            }

            if(localStorage.getItem(`remember_filters/${page_name}`)) {
                remember_filters = JSON.parse(localStorage.getItem(`remember_filters/${page_name}`));
                submit_filter_data = JSON.parse(JSON.stringify(remember_filters));
                if(remember_filters.filters_table.length == 0) {
                    remember_filters.filters_table.push({
                        id: first_column_id,
                        value: "",
                        text: column_names[0].text,
                        type: column_names[0].type,
                        field: column_names[0].field,
                        details: column_names[0].details,
                        operator: 'Is',
                    });
                }
                var localStorage_filter = remember_filters.filters_table;
                for(let super_key in localStorage_filter) {
                    var data = localStorage_filter[super_key];
                    if(data.type == 'timestamp' || data.type == 'date') {
                        $('#filters-table tbody').append(`
                            <tr class="filter">
                                <td class="field" data-number="${+data.id}" data-type="${data.type}" data-name="${data.text}">
                                    <input type="checkbox" class="remove-filter${+data.id}" style="margin-right: 5px" checked>
                                    <label >${data.text}</label>
                                </td>
                                <td class="operator">
                                    <select id="table-operator${+data.id}" style="width: 300px;">
                                        <option value="Is">Is</option>
                                        <option value="Before">Before</option>
                                        <option value="After">After</option>
                                        <option value="Between">Between</option>
                                    </select>
                                </td>
                                <td class="values" values-number="${+data.id}">
                                    <div class="from-date" style="display:inline-block;">
                                        <label>From Date: </label>
                                        <input type="text" class="datepicker_start_date" data-number="${+data.id}" style="z-index: 10000;width: 200px;" />
                                        <span data-number="${+data.id}"></span>
                                    </div>
                                    <div class="to-date" style="display:none;margin-left: 20px;">
                                        <label>To Date: </label>
                                        <input type="text" class="datepicker_end_date" data-number="${+data.id}" style="z-index: 10000;width: 200px;" />
                                        <span data-number="${+data.id}"></span>
                                    </div>
                                </td>
                            </tr>
                        `);

                        if(data.operator) {
                            $(`[id^=table-operator] option[value="${data.operator}"]`).attr('selected', true);
                            if(data.operator == 'Between') {
                                $('.to-date').css({'display': 'inline-block'});
                            }
                        }
                        $(`#table-operator${+data.id}`).select2({
                            theme: "classic",
                            width: 'resolve',
                            dropdownAutoWidth : true,
                            minimumResultsForSearch: -1,
                        });
                        $(".datepicker_start_date").datepicker({
                            dateFormat: "yy-mm-dd",
                            "onSelect": function(date) {
                                minDateFilter = new Date(date).getTime();
                                saved_minDateFilter = {minDateFilter: minDateFilter, date: date};
                                // update remember_filters for remember when reload
                                var foundIndex = remember_filters.filters_table.findIndex(x => x.id == $(this).attr('data-number'));
                                remember_filters.filters_table[foundIndex].value_start = date;
                                localStorage.setItem(`remember_filters/${page_name}`, JSON.stringify(remember_filters));
                                // localStorage.setItem(`saved_minDateFilter/${page_name}`, JSON.stringify(saved_minDateFilter));
                            },
                            "beforeShow": function() {
                                setTimeout(function(){
                                    $('.ui-datepicker').css('z-index', 99);
                                }, 0);
                            }
                        }).keyup(function() {
                            minDateFilter = new Date(this.value).getTime();
                            saved_minDateFilter = {minDateFilter: minDateFilter, date: this.value};
                            // update remember_filters for remember when reload
                            var foundIndex = remember_filters.filters_table.findIndex(x => x.id == $(this).attr('data-number'));
                            remember_filters.filters_table[foundIndex].value_start = this.value;
                            localStorage.setItem(`remember_filters/${page_name}`, JSON.stringify(remember_filters));
                            // localStorage.setItem(`saved_minDateFilter/${page_name}`, JSON.stringify(saved_minDateFilter));
                            if(isValidDate(this.value) || this.value == '') {
                                
                            }
                        });
                        $(".datepicker_end_date").datepicker({
                            dateFormat: "yy-mm-dd",
                            // defaultDate: $.datepicker.parseDate("y-m-d", data.value_end),
                            "onSelect": function(date) {
                                maxDateFilter = new Date(date).getTime();
                                saved_maxDateFilter = {maxDateFilter: maxDateFilter, date: date};
                                if(isValidDate(this.value) || this.value == '') {
                                    // update remember_filters for remember when reload
                                    var foundIndex = remember_filters.filters_table.findIndex(x => x.id == $(this).attr('data-number'));
                                    remember_filters.filters_table[foundIndex].value_end = date;
                                    localStorage.setItem(`remember_filters/${page_name}`, JSON.stringify(remember_filters));
                                    // localStorage.setItem(`saved_maxDateFilter/${page_name}`, JSON.stringify(saved_maxDateFilter));
                                }
                            },
                            "beforeShow": function() {
                                setTimeout(function(){
                                    $('.ui-datepicker').css('z-index', 99);
                                }, 0);
                            }
                        }).keyup(function() {
                            maxDateFilter = new Date(this.value).getTime();
                            saved_maxDateFilter = {maxDateFilter: maxDateFilter, date: this.value}; 
                            if(isValidDate(this.value) || this.value == '') {
                                // update remember_filters for remember when reload
                                var foundIndex = remember_filters.filters_table.findIndex(x => x.id == $(this).attr('data-number'));
                                remember_filters.filters_table[foundIndex].value_end = this.value;
                                localStorage.setItem(`remember_filters/${page_name}`, JSON.stringify(remember_filters));
                                // localStorage.setItem(`saved_maxDateFilter/${page_name}`, JSON.stringify(saved_maxDateFilter));
                            }
                        });
                        $('.ui-datepicker').css('z-index', '99 !important');
                        $(`span[data-number="${+data.id}"]`).css({'position': 'relative', 'top': '4px'}).append(`
                            <i class="voyager-calendar" style="font-size: 20px;"></i>
                        `);
                        $('[id^=table-operator]').each(function() {
                            $(this).on('select2:select',(e) => {
                                var data = e.params.data;
                                var id = ($(e.target).attr('id'));
                                var field_id = id.substr(14);
                                var foundIndex = remember_filters.filters_table.findIndex(x => x.id == field_id);
                                remember_filters.filters_table[foundIndex].operator = data.id;
                                if(data.id == 'Between') {
                                    $(`[values-number="${field_id}"]`).children('.to-date').css({'display': 'inline-block'});
                                } else {
                                    $(`[values-number="${field_id}"]`).children('.to-date').css({'display': 'none'});
                                }
                                localStorage.setItem(`remember_filters/${page_name}`, JSON.stringify(remember_filters));
                            });
                        });
                        $(`.datepicker_end_date`).datepicker('setDate', data.value_end);
                        $(".datepicker_start_date").datepicker('setDate', data.value_start);
                        // $('.ui-datepicker-current-day').click();
                        // $('.ui-datepicker-current-day').click();
                    } else if(data.type == "select_dropdown"){
                        $('#filters-table tbody').append(`
                            <tr class="filter">
                                <td class="field" data-number="${+data.id}" data-type="${data.type}" data-name="${data.text}">
                                    <input type="checkbox" class="remove-filter${+data.id}" style="margin-right: 5px" checked>
                                    <label >${data.text}</label>
                                </td>
                                <td class="values">
                                    <span style="">
                                        <select id="table-filter${+data.id}" style="width: 300px;">
                                            <option value="">None</option>
                                        </select>
                                    </span>
                                </td>
                            </tr>
                        `);
                        var selectedData = Object.values(data.details.options);
                        var column_to_filter = (+data.id);
                        @if(intval(request()->showSoftDeleted) == 1 || $dataType->slug == 'transactions' || $dataType->slug == 'orders')
                        column_to_filter = (+data.id - 1);
                        @endif
                        
                        //remove all double option
                        selectedData = [...new Set(selectedData)];
                        
                        (function() { // don't leak
                            var elm = document.getElementById(`table-filter${+data.id}`), // get the select
                                df = document.createDocumentFragment(); // create a document fragment to hold the options while we create them
                            for (let key in selectedData) {
                                var option = document.createElement('option'); // create the option element
                                var text = selectedData[key].replace(/^<div[^>]*>|^<span[^>]*>|^<a[^>]*>|<\/span>|<\/a>|<\/div>$/g, '');
                                if(data.text == 'Status' && text == 'Active') {
                                    option.value = 1;
                                } else if(data.text == 'Status' && text == 'Inactive') {
                                    option.value = 0;
                                } else {
                                    option.value = text; // set the value property
                                }
                                if(option.value == data.value) {
                                    option.setAttribute('selected', true);
                                }
                                option.appendChild(document.createTextNode(text)); // set the textContent in a safe way.
                                df.appendChild(option); // append the option to the document fragment
                            }
                            elm.appendChild(df); // append the document fragment to the DOM. this is the better way rather than setting innerHTML a bunch of times (or even once with a long string)
                        }());
                        $(`#table-filter${+data.id}`).select2({
                            theme: "classic",
                            width: 'resolve',
                            data: data,
                            dropdownAutoWidth : true
                        });
                    } else {
                        $('#filters-table tbody').append(`
                            <tr class="filter">
                                <td class="field" data-number="${+data.id}" data-type="${data.type}" data-name="${data.text}">
                                    <input type="checkbox" class="remove-filter${+data.id}" style="margin-right: 5px" checked>
                                    <label >${data.text}</label>
                                </td>
                                <td class="values">
                                    <span style="">
                                        <input type="text" id="table-filter${+data.id}" value="${data.value}" style="width: 300px;">
                                    </span>
                                </td>
                            </tr>
                        `);
                    }
                    //disable select which column to filter
                    $('#filter-column').find(`option[value="${+data.id - 1}"]`).prop({ disabled: true });
                }
            } else {
                if(column_names[0].type == 'relationship' || column_names[0].type == 'text') {
                    // append the default filter
                    $('#filters-table tbody').append(`
                        <tr class="filter">
                            <td class="field" data-number="${+column_names[0].id + 1}" data-type="${column_names[0].type}" data-name="${column_names[0].text}">
                                <input type="checkbox" class="remove-filter${+column_names[0].id + 1}" style="margin-right: 5px" checked>
                                <label >${column_names[0].text}</label>
                            </td>
                            <td class="values">
                                <span style="">
                                    <input type="text" id="table-filter${+column_names[0].id + 1}" style="width: 300px;" placeholder="">
                                </span>
                            </td>
                        </tr>
                    `);
                    // disable option when first reload
                    $('#filter-column').find('option[value="0"]').attr('disabled', 'disabled');
                } else if(column_names[0].type == "select_dropdown") {
                    $('#filters-table tbody').append(`
                            <tr class="filter">
                                <td class="field" data-number="${+column_names[0].id + 1}" data-type="${column_names[0].type}" data-name="${column_names[0].text}">
                                    <input type="checkbox" class="remove-filter${+column_names[0].id + 1}" style="margin-right: 5px" checked>
                                    <label >${column_names[0].text}</label>
                                </td>
                                <td class="values">
                                    <span style="">
                                        <select id="table-filter${+column_names[0].id + 1}" style="width: 300px;">
                                            <option value="">None</option>
                                        </select>
                                    </span>
                                </td>
                            </tr>
                        `);

                    $(`#table-filter${+data.id + 1}`).select2({
                        theme: "classic",
                        width: 'resolve',
                        dropdownAutoWidth : true
                    });

                    var selectedData = Object.values(data.details.options);
                    var first_filter_index = 1;
                    @if(intval(request()->showSoftDeleted) == 1 || $dataType->slug == 'transactions' || $dataType->slug == 'orders')
                    first_filter_index = 0;
                    @endif
                    
                    //remove all double option
                    selectedData = [...new Set(selectedData)];

                    (function() { // don't leak
                        var elm = document.getElementById(`table-filter1`), // get the select
                            df = document.createDocumentFragment(); // create a document fragment to hold the options while we create them
                            for (let key in selectedData) {
                                var option = document.createElement('option'); // create the option element
                                var text = selectedData[key].replace(/^<div[^>]*>|^<span[^>]*>|^<a[^>]*>|<\/span>|<\/a>|<\/div>$/g, '');
                                if(data.text == 'Status' && text == 'Active') {
                                    option.value = 1;
                                } else if(data.text == 'Status' && text == 'Inactive') {
                                    option.value = 0;
                                } else {
                                    option.value = text; // set the value property
                                }
                                option.appendChild(document.createTextNode(text)); // set the textContent in a safe way.
                                df.appendChild(option); // append the option to the document fragment
                            }
                        elm.appendChild(df); // append the document fragment to the DOM. this is the better way rather than setting innerHTML a bunch of times (or even once with a long string)
                    }());
                }
            }

            $('#filter-column').each(function() {
                $(this).on('#filter-column select2:select',(e) => {
                    var data = e.params.data;
                    if(data.type == 'timestamp' || data.type == 'date') {
                        $('#filters-table tbody').append(`
                            <tr class="filter">
                                <td class="field" data-number="${+data.id + 1}" data-type="${data.type}" data-name="${data.text}">
                                    <input type="checkbox" class="remove-filter${+data.id + 1}" style="margin-right: 5px" checked>
                                    <label >${data.text}</label>
                                </td>
                                <td class="operator">
                                    <select id="table-operator${+data.id + 1}" style="width: 300px;">
                                        <option value="Is">Is</option>
                                        <option value="Before">Before</option>
                                        <option value="After">After</option>
                                        <option value="Between">Between</option>
                                    </select>
                                </td>
                                <td class="values" values-number="${+data.id + 1}">
                                    <div class="from-date" style="display:inline-block;">
                                        <label>From Date: </label>
                                        <input type="text" class="datepicker_start_date" data-number="${+data.id + 1}" style="z-index: 10000;width: 200px;" />
                                        <span data-number="${+data.id + 1}"></span>
                                    </div>
                                    <div class="to-date" style="display:none;margin-left: 20px;">
                                        <label>To Date: </label>
                                        <input type="text" class="datepicker_end_date" data-number="${+data.id + 1}" style="z-index: 10000;width: 200px;" />
                                        <span data-number="${+data.id + 1}"></span>
                                    </div>
                                </td>
                            </tr>
                        `);
                        $(`#table-operator${+data.id + 1}`).select2({
                            theme: "classic",
                            width: 'resolve',
                            dropdownAutoWidth : true,
                            minimumResultsForSearch: -1,
                        });
                        $(".datepicker_start_date").datepicker({
                            dateFormat: "yy-mm-dd",
                            "onSelect": function(date) {
                                minDateFilter = new Date(date).getTime();
                                saved_minDateFilter = {minDateFilter: minDateFilter, date: date};
                                // update remember_filters for remember when reload
                                var foundIndex = remember_filters.filters_table.findIndex(x => x.id == $(this).attr('data-number'));
                                remember_filters.filters_table[foundIndex].value_start = date;
                                localStorage.setItem(`remember_filters/${page_name}`, JSON.stringify(remember_filters));
                                // localStorage.setItem(`saved_minDateFilter/${page_name}`, JSON.stringify(saved_minDateFilter));
                            },
                            "beforeShow": function() {
                                setTimeout(function(){
                                    $('.ui-datepicker').css('z-index', 99);
                                }, 0);
                            }
                        }).keyup(function() {
                            minDateFilter = new Date(this.value).getTime();
                            if(isValidDate(this.value) || this.value == '') {
                                var foundIndex = remember_filters.filters_table.findIndex(x => x.id == $(this).attr('data-number'));
                                remember_filters.filters_table[foundIndex].value_start = this.value;
                                saved_minDateFilter = {minDateFilter: minDateFilter, date: this.value};
                                localStorage.setItem(`remember_filters/${page_name}`, JSON.stringify(remember_filters));
                                // localStorage.setItem(`saved_minDateFilter/${page_name}`, JSON.stringify(saved_minDateFilter));
                            }
                        });
                        $(".datepicker_end_date").datepicker({
                            dateFormat: "yy-mm-dd",
                            "onSelect": function(date) {
                                maxDateFilter = new Date(date).getTime();
                                saved_maxDateFilter = {maxDateFilter: maxDateFilter, date: date}; 
                                var foundIndex = remember_filters.filters_table.findIndex(x => x.id == $(this).attr('data-number'));
                                remember_filters.filters_table[foundIndex].value_end = date;
                                localStorage.setItem(`remember_filters/${page_name}`, JSON.stringify(remember_filters));
                                // localStorage.setItem(`saved_maxDateFilter/${page_name}`, JSON.stringify(saved_maxDateFilter));
                            },
                            "beforeShow": function() {
                                setTimeout(function(){
                                    $('.ui-datepicker').css('z-index', 99);
                                }, 0);
                            }
                        }).keyup(function() {
                            maxDateFilter = new Date(this.value).getTime();
                            if(isValidDate(this.value)) {
                                saved_maxDateFilter = {maxDateFilter: maxDateFilter, date: date}; 
                                var foundIndex = remember_filters.filters_table.findIndex(x => x.id == $(this).attr('data-number'));
                                remember_filters.filters_table[foundIndex].value_end = this.value;
                                localStorage.setItem(`remember_filters/${page_name}`, JSON.stringify(remember_filters));
                                // localStorage.setItem(`saved_maxDateFilter/${page_name}`, JSON.stringify(saved_maxDateFilter));
                            }
                        });
                        $('.ui-datepicker').css('z-index', '99 !important');
                        $(`span[data-number="${+data.id + 1}"]`).css({'position': 'relative', 'top': '4px'}).append(`
                            <i class="voyager-calendar" style="font-size: 20px;"></i>
                        `);
                        
                        $('[id^=table-operator]').each(function() {
                            $(this).on('select2:select',(e) => {
                                var data = e.params.data;
                                var id = ($(e.target).attr('id'));
                                var field_id = id.substr(14);
                                var foundIndex = remember_filters.filters_table.findIndex(x => x.id == field_id);
                                console.log(id, remember_filters, foundIndex);
                                remember_filters.filters_table[foundIndex].operator = data.id;
                                if(data.id == 'Between') {
                                    $(`[values-number="${field_id}"]`).children('.to-date').css({'display': 'inline-block'});
                                } else {
                                    $(`[values-number="${field_id}"]`).children('.to-date').find('input').val('');
                                    $(`[values-number="${field_id}"]`).children('.to-date').css({'display': 'none'});
                                }
                                localStorage.setItem(`remember_filters/${page_name}`, JSON.stringify(remember_filters));
                            });
                        });
                    } else if(data.type == 'select_dropdown') {
                        $('#filters-table tbody').append(`
                            <tr class="filter">
                                <td class="field" data-number="${+data.id + 1}" data-type="${data.type}" data-name="${data.text}">
                                    <input type="checkbox" class="remove-filter${+data.id + 1}" style="margin-right: 5px" checked>
                                    <label >${data.text}</label>
                                </td>
                                <td class="values">
                                    <span style="">
                                        <select id="table-filter${+data.id + 1}" style="width: 300px;">
                                            <option value="">None</option>
                                        </select>
                                    </span>
                                </td>
                            </tr>
                        `);

                        $(`#table-filter${+data.id + 1}`).select2({
                            theme: "classic",
                            width: 'resolve',
                            dropdownAutoWidth : true
                        });
                        // var rows = table.rows({selected:true}).indexes();
                        // var selectedData = table.cells(rows, (+data.id + 1)).data();
                        
                        var selectedData = Object.values(data.details.options);
                        var column_to_filter = (+data.id + 1);
                        @if(intval(request()->showSoftDeleted) == 1 || $dataType->slug == 'transactions' || $dataType->slug == 'orders')
                        column_to_filter = (+data.id);
                        @endif
                        
                        // var selectedData = table.columns(column_to_filter).data()[0];
                        //remove all double option
                        selectedData = [...new Set(selectedData)];

                        (function() { // don't leak
                            var elm = document.getElementById(`table-filter${+data.id + 1}`), // get the select
                                df = document.createDocumentFragment(); // create a document fragment to hold the options while we create them
                            for (let key in selectedData) {
                                var option = document.createElement('option'); // create the option element
                                var text = selectedData[key].replace(/^<div[^>]*>|^<span[^>]*>|^<a[^>]*>|<\/span>|<\/a>|<\/div>$/g, '');
                                if(data.text == 'Status' && text == 'Active') {
                                    option.value = 1;
                                } else if(data.text == 'Status' && text == 'Inactive') {
                                    option.value = 0;
                                } else {
                                    option.value = text; // set the value property
                                }
                                option.appendChild(document.createTextNode(text)); // set the textContent in a safe way.
                                df.appendChild(option); // append the option to the document fragment
                            }
                            elm.appendChild(df); // append the document fragment to the DOM. this is the better way rather than setting innerHTML a bunch of times (or even once with a long string)
                        }());
                    } else {
                        $('#filters-table tbody').append(`
                            <tr class="filter">
                                <td class="field" data-number="${+data.id + 1}" data-type="${data.type}" data-name="${data.text}">
                                    <input type="checkbox" class="remove-filter${+data.id + 1}" style="margin-right: 5px" checked>
                                    <label >${data.text}</label>
                                </td>
                                <td class="values">
                                    <span style="">
                                        <input type="text" id="table-filter${+data.id + 1}" style="width: 300px;">
                                    </span>
                                </td>
                            </tr>
                        `);
                    }
                    //disable select which column to filter
                    $('#filter-column').find(':selected').prop({ disabled: true });
                    //set for remember after reload page
                    var each_row_filter = [];
                    var tagName = '';
                    var value = '';
                    if(data.type != 'timestamp' && data.type != 'date') {
                        remember_filters.filters_table.push({
                            id: +data.id + 1,
                            value: '',
                            text: data.text,
                            type: data.type,
                            field: data.field,
                            details: data.details,
                            operator: 'Is',
                        });
                    } else {
                        remember_filters.filters_table.push({
                            id: +data.id + 1,
                            value_start: '',
                            value_end: '',
                            text: data.text,
                            type: data.type,
                            field: data.field,
                            details: data.details,
                            operator: 'Is',
                        });
                    }
                    localStorage.setItem(`remember_filters/${page_name}`, JSON.stringify(remember_filters));
                });
            });

            // click checkbox to remove row of filter
            $(document).on('click', '[class^=remove-filter]', function(e) {
                var class_name = ($(e.target).attr('class'));
                $('#filter-column').val(null).trigger('change');
                //unlock the select of column
                $('#filter-column').find(`option[value=${parseInt(class_name.charAt(class_name.length - 1)) - 1}]`).prop({ disabled: false });
                
                var foundIndex = remember_filters.filters_table.findIndex(x => x.id == class_name.charAt(class_name.length - 1));
                if (foundIndex > -1) {
                    if((remember_filters.filters_table[foundIndex].type == 'timestamp' || remember_filters.filters_table[foundIndex].type == 'date')) {
                        minDateFilter = "";
                        maxDateFilter = "";
                    }
                    remember_filters.filters_table.splice(foundIndex, 1);
                }
                $("[class^=remove-filter]:checkbox:not(:checked)").closest('.filter').remove();
                localStorage.setItem(`remember_filters/${page_name}`, JSON.stringify(remember_filters));
            });

            $('.apply-filter').click(function() {
                submit_filter_data = remember_filters;
                var data_filter = JSON.stringify(remember_filters);
                $('#data-filter').val(data_filter);
            });

            $('.clear-filter').click(function() {
                var $first_field = $('#filters-table tr:first-child').find('.field');
                var only_id = $first_field.attr('data-number');
                var row_filter = [];
                if($first_field.attr('data-type') == 'timestamp' || $first_field.attr('data-type') == 'date') {
                    row_filter.push({
                        id: $first_field.attr('data-number'),
                        value_start: '',
                        value_end: '',
                        text: $first_field.attr('data-name'),
                        type: $first_field.attr('data-type'),
                        field: remember_filters.filters_table[0].field,
                        details: remember_filters.filters_table[0].details,
                        operator: 'Is',
                    });
                } else {
                    row_filter.push({
                        id: $first_field.attr('data-number'),
                        value: '',
                        text: $first_field.attr('data-name'),
                        type: $first_field.attr('data-type'),
                        field: remember_filters.filters_table[0].field,
                        details: remember_filters.filters_table[0].details,
                        operator: 'Is',
                    });
                }
                remember_filters.filters_table = row_filter;
                $('#filters-table tr:not(:first-child)').each(function(){
                    var id = $(this).find('.field').attr('data-number');
                    $('#filter-column').find(`option[value=${parseInt(id) - 1}]`).prop({ disabled: false });
                });
                $('#filters-table tr:not(:first-child)').remove();
                
                $('[id^=table-filter]').val('').trigger('change');
                $('input').val('');
                $('[id^=table-operator]').val('Is').trigger('change');
                $('.to-date').css({'display': 'none'});

                localStorage.setItem(`remember_filters/${page_name}`, JSON.stringify(remember_filters));
            });

            //select to filter and trigger search select of Datatable
            $(document).on('keyup', '[id^=table-filter]', function(e) {
                var option_value = $(e.target).val();
                var id = ($(e.target).attr('id'));
                // update remember_filters for remember when reload
                var foundIndex = remember_filters.filters_table.findIndex(x => x.id == id.charAt(id.length - 1));
                remember_filters.filters_table[foundIndex].value = option_value;
                localStorage.setItem(`remember_filters/${page_name}`, JSON.stringify(remember_filters));
            });

            //select to filter and trigger search select of Datatable
            $(document).on('select2:select', '[id^=table-filter]', function(e) {
                var value = e.params.data;
                var id = ($(e.target).attr('id'));
                var option_value = value.id;
                //check if we select None option
                if(value.text == 'None') {
                    option_value = '';
                }
                // update remember_filters for remember when reload
                var foundIndex = remember_filters.filters_table.findIndex(x => x.id == id.charAt(id.length - 1));
                remember_filters.filters_table[foundIndex].value = option_value;
                localStorage.setItem(`remember_filters/${page_name}`, JSON.stringify(remember_filters));
            });


            $('.export-csv').click( function(){
                var data_filter = JSON.stringify(submit_filter_data);
                $('#data_export_csv').val(data_filter);
            })
            $('.export-pdf').click( function(){
                var data_filter = JSON.stringify(submit_filter_data);
                $('#data_export_pdf').val(data_filter);
            })


            // hide search bar of datatable
            $('.dataTables_filter').hide();

            // rotate collapse icon
            let collapse_filter = false;
            $('.filter-collapse').on('click', function() {
                collapse_filter = !collapse_filter;
                if(collapse_filter == true) {
                    $('.voyager-sort-desc').css('transform', 'rotate(270deg)');
                } else {
                    $('.voyager-sort-desc').css('transform', 'rotate(0deg)');
                }
            });

            // check if string is number
            function isNumeric(str) {
                if (typeof str != "string") return false // we only process strings!  
                return !isNaN(str) && // use type coercion to parse the _entirety_ of the string (`parseFloat` alone does not do this)...
                        !isNaN(parseFloat(str)) // ...and ensure strings of whitespace fail
            }
            function formatState (state) {
                state = '';
                return state;
            };

            function isValidDate(dateString) {
                var regEx = /^\d{4}-\d{2}-\d{2}$/;
                if(!dateString.match(regEx)) return false;  // Invalid format
                var d = new Date(dateString);
                var dNum = d.getTime();
                if(!dNum && dNum !== 0) return false; // NaN value, Invalid date
                return d.toISOString().slice(0,10) === dateString;
            }

        });


        var deleteFormAction;
        $('td').on('click', '.delete', function (e) {
            $('#delete_form')[0].action = '{{ route('voyager.'.$dataType->slug.'.destroy', '__id') }}'.replace('__id', $(this).data('id'));
            $('#delete_modal').modal('show');
        });

        @if($usesSoftDeletes)
            @php
                $params = [
                    's' => $search->value,
                    'filter' => $search->filter,
                    'key' => $search->key,
                    'order_by' => $orderBy,
                    'sort_order' => $sortOrder,
                ];
            @endphp
            $(function() {
                $('#show_soft_deletes').change(function() {
                    if ($(this).prop('checked')) {
                        $('#dataTable').before('<a id="redir" href="{{ (route('voyager.'.$dataType->slug.'.index', array_merge($params, ['showSoftDeleted' => 1]), true)) }}"></a>');
                    }else{
                        $('#dataTable').before('<a id="redir" href="{{ (route('voyager.'.$dataType->slug.'.index', array_merge($params, ['showSoftDeleted' => 0]), true)) }}"></a>');
                    }

                    $('#redir')[0].click();
                })
            })
        @endif
        $('input[name="row_id"]').on('change', function () {
            var ids = [];
            $('input[name="row_id"]').each(function() {
                if ($(this).is(':checked')) {
                    ids.push($(this).val());
                }
            });
            $('.selected_ids').val(ids);
        });
        
    </script>
@stop
--}}