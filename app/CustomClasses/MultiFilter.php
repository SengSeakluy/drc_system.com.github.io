<?php

namespace App\CustomClasses;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use TCG\Voyager\Models\Role;

class MultiFilter {
    public function __construct(){
        
    }

    public function filter($request, $fields, $dataType_model_name, $field_relationship = []){
        // set a model for query
        $model = app($dataType_model_name);
        $query = $model::select('*');

        $filter = null;
        $values_filter = '';
        $key_filter = '';
        $operator_filter = [];
        $row_number = 1;
        //check for request data from main page or pagination page
        if(isset($request->data_export_filter) && $request->get('s') == null && $request->get('key') == null && $request->get('filter') == null) {
            foreach(json_decode($request->data_export_filter)->filters_table as $key => $filter_column) {
                if($filter_column->type == 'relationship'){
                    $filter[$filter_column->field] = ['type' => $filter_column->type, 'details' => $filter_column->details, 'value' => $filter_column->value];
                    $operator_filter[] = '=';
                } else if($filter_column->type == 'timestamp' || $filter_column->type == 'date'){
                    $filter[$filter_column->field] = ['type' => $filter_column->type, 'value_start' => $filter_column->value_start, 'value_end' => $filter_column->value_end];
                    $operator_filter[] = $filter_column->operator;
                }else {
                    $filter[$filter_column->field] = $filter_column->value;
                    $operator_filter[] = '=';
                }
            }
        }

        //prepare data for search
        if(isset($filter)) {
            $values_filter = array_values($filter);
            $key_filter = array_keys($filter);
        } else {
            $values_filter = array_values($request->get('s') ?? []);
            $key_filter = array_values($request->get('key') ?? []);
            $operator_filter = array_values($request->get('filter') ?? []);
        }
        $search = (object) ['value' => $values_filter, 'key' => $key_filter, 'filter' => $operator_filter];

        if(count($search->value) != 0 && count($search->key) != 0 && count($search->value) != 0) {
            for($i = 0; $i < count($search->value); $i++) {
                $type = $search->value[$i]['type'] ?? null;
                if($type == 'relationship' && isset($search->value[$i]['details']) && (isset($search->value[$i]['value']) && $search->value[$i]['value'] != '' && $search->key[$i] && $search->filter)){
                    $details = $search->value[$i]['details'];
                    if(gettype($details) == 'object') {
                        $details = (array) $search->value[$i]['details'];
                    } 
                    $search_value = $search->value[$i]['value'];
                    $search_id = app($details['model'])::where($details['label'], 'LIKE', '%'.$search_value.'%')->select('id')->get()->toArray();
                    $query->whereIn($details['column'], $search_id);
                } else if(($type == 'timestamp' || $type == 'date') && (isset($search->value[$i]['value_start']) && ($search->value[$i]['value_start'] != '' || $search->value[$i]['value_end'] != '') && $search->key[$i] && $search->filter)){
                    $field_column = $search->key[$i];
                    $search_value = $search->value[$i];
                    $search_value['value_start'] = Carbon::parse($search_value['value_start'], Auth::user()->timezone)->setTimezone(config('app.timezone'))->toDateTimeString();
                    
                    if($search->filter[$i] == 'Is') {
                        $tomorrow_of_startDay = Carbon::parse($search_value['value_start'], Auth::user()->timezone)->setTimezone(config('app.timezone'))->addDay()->toDateTimeString();
                        $query->where($field_column, '>=', $search_value['value_start'])
                                ->where($field_column, '<=', $tomorrow_of_startDay);
                    } else if(isset($search_value['value_start']) && ($search->filter[$i] == 'Before' || $search->filter[$i] == 'After')){
                        $search_filter = ($search->filter[$i] == 'Before') ? '<=' : '>=';
                        $query->where($field_column, $search_filter, $search_value['value_start']);
                    } else if($search->filter[$i] == 'Between') {
                        if($search_value['value_end'] == '' || $search_value['value_end'] == null) {
                            $search_value['value_end'] = Carbon::now()->setTimezone(config('app.timezone'))->toDateTimeString();
                        } else {
                            $search_value['value_end'] = Carbon::parse($search_value['value_end'], Auth::user()->timezone)->setTimezone(config('app.timezone'))->addDay()->toDateTimeString();
                        }
                        if($search->value[$i]['value_start'] != '' && $search->value[$i]['value_start'] != null) {
                            $query->where($field_column, '>=', $search_value['value_start'])
                                    ->where($field_column, '<=', $search_value['value_end']);
                        } else {
                            $query->where($field_column, '<=', $search_value['value_end']);
                        }
                    }
                } else if(gettype($search->value[$i]) != 'array' && $search->value[$i] != '' && $search->key[$i] && $search->filter) {
                    $search_filter = ($search->filter == 'equals') ? '=' : 'LIKE';
                    $search_value = ($search->filter == 'equals') ? $search->value[$i] : '%'.$search->value[$i].'%';
                    $query->where($search->key[$i], $search_filter, $search_value);
                }
            }
        }

        // dd(app($dataType_model_name)->getTable());
        $slug = app($dataType_model_name)->getTable();
        // check whether the $slug has column merchant_id
        // if(Auth::user()->id != config('common.superadmin_user') && Auth::user()->role_id != config('common.superadmin_role')){
            
        //     if(Schema::hasColumn($slug, 'merchant_id') && Auth::user()->merchant_id>0){
        //         $query->where('merchant_id', '=', Auth::user()->merchant_id);
        //     } else {
        //         // filter data based on selected merchant
        //         $merchantList = [];
        //         $roleInfo = Role::where('id', Auth::user()->role_id)->first();
                
        //         // if has configure merchant filter data
        //         if ($roleInfo->merchant_list) {
        //             foreach ((array) json_decode($roleInfo->merchant_list) as $index_merchant => $merchant) {
        //                 $merchantList[] = $index_merchant;
        //             }

        //             if (Schema::hasColumn($slug, 'merchant_id')) {
        //                 $query->whereIn('merchant_id', $merchantList);
        //             }

        //             // if enable role merchant, show only selected merchant
        //             if (in_array($slug,array('merchant'))) {
        //                 $query->whereIn('id', $merchantList);
        //             }
        //         }
        //     }
        // }

        if(app($dataType_model_name)->getTable() == 'users' || app($dataType_model_name)->getTable() == 'ip_white_list') {
            $query->where('type', 0);
        }
        $query->select($fields);
        $dataTypeContent = call_user_func([$query, 'get']);
        foreach($field_relationship as $field_name => $details) {
            foreach($dataTypeContent as $data) {
                $relationship_data = app($details->model)::where('id', $data->{$field_name})->value($details->label);
                $data->{$field_name} = $relationship_data;
            }
        }
        foreach($dataTypeContent as $data){
            if(isset($data['status']) && is_numeric($data['status'])){
                $data['status'] = $data['status'] == 1 ? 'Active' : 'Inactive';
            }
            if(isset($data['created_at'])) {
                $data['created_at'] = Carbon::parse($data['created_at'])->setTimezone(config('app.client_timezone'))->toDateTimeString();
            }
            if(isset($data['payment_date'])) {
                $data['payment_date'] = Carbon::parse($data['payment_date'])->setTimezone(config('app.client_timezone'))->toDateTimeString();
            }
            foreach($data->toArray() as $key => $column) {
                if(!isset($column) || $column === '') {
                    $data[$key] = 'N/A';
                }
            }

            if(isset($data['narrative'])){
                $narrative = json_decode($data['narrative']);
                $data['narrative'] = implode(", ",(array) $narrative);
            }

            $data['id'] = $row_number++;
        }

        return $dataTypeContent;
    }
}