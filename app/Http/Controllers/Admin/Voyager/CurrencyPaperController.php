<?php

namespace App\Http\Controllers\Admin\Voyager;

use TCG\Voyager\Database\Schema\SchemaManager;
use TCG\Voyager\Facades\Voyager;
use App\CustomClasses\ValidateData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Admin\CustomVoyagerBaseController;
use Redirect,Response;


class CurrencyPaperController extends CustomVoyagerBaseController
{
   //
   public function index(Request $request)
   {
      $slug = $this->getSlug($request);
      $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();
      
      // Check permission
      $this->authorize('browse', app($dataType->model_name));
      $getter = $dataType->server_side ? 'paginate' : 'get';
      
      $filter = null;
      $values_filter = '';
      $key_filter = '';
      $operator_filter = [];
      if(isset($request->data_filter) && $request->get('s') == null && $request->get('key') == null && $request->get('filter') == null) {
         foreach(json_decode($request->data_filter)->filters_table as $key => $filter_column) {
               if($filter_column->type == 'relationship'){
                  $filter_column->value = $filter_column->value !== '' ? $filter_column->value : '__None__';
                  $filter[$filter_column->field] = ['type' => $filter_column->type, 'details' => $filter_column->details, 'value' => $filter_column->value];
                  $operator_filter[] = '=';
               } else if($filter_column->type == 'timestamp' || $filter_column->type == 'date'){
                  $filter[$filter_column->field] = ['type' => $filter_column->type, 'value_start' => $filter_column->value_start, 'value_end' => $filter_column->value_end];
                  $operator_filter[] = $filter_column->operator;
               }else {
                  $filter_column->value = $filter_column->value !== '' ? $filter_column->value : '__None__';
                  $filter[$filter_column->field] = $filter_column->value;
                  $operator_filter[] = '=';
               }
         }
      }
 
      if(isset($filter)) {
         $values_filter = array_values($filter);
         $key_filter = array_keys($filter);
      } else {
         $values_filter = array_values($request->get('s') ?? []);
         $key_filter = array_values($request->get('key') ?? []);
         $operator_filter = array_values($request->get('filter') ?? []);
      }
      $search = (object) ['value' => $values_filter, 'key' => $key_filter, 'filter' => $operator_filter];
      // dd($search);

      $searchNames = [];
      if ($dataType->server_side) {
         $searchable = SchemaManager::describeTable(app($dataType->model_name)->getTable())->pluck('name')->toArray();
         $dataRow = Voyager::model('DataRow')->whereDataTypeId($dataType->id)->get();
         foreach ($searchable as $key => $value) {
               $field = $dataRow->where('field', $value)->first();
               $displayName = ucwords(str_replace('_', ' ', $value));
               if ($field !== null) {
                  $displayName = $field->getTranslatedAttribute('display_name');
               }
               $searchNames[$value] = $displayName;
         }
      }


      $orderBy = $request->get('order_by', $dataType->order_column);
      $sortOrder = $request->get('sort_order', $dataType->order_direction);
      $usesSoftDeletes = false;
      $showSoftDeleted = false;

      
      // Next Get or Paginate the actual content from the MODEL that corresponds to the slug DataType
      if (strlen($dataType->model_name) != 0) {
         $model = app($dataType->model_name);

         if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope'.ucfirst($dataType->scope))) {
               $query = $model->{$dataType->scope}();
         } else {
               $query = $model::select('*');
         }
         
         // Use withTrashed() if model uses SoftDeletes and if toggle is selected
         if ($model && in_array(SoftDeletes::class, class_uses_recursive($model)) && Auth::user()->can('delete', app($dataType->model_name))) {
               $usesSoftDeletes = true;

               if ($request->get('showSoftDeleted')) {
                  $showSoftDeleted = true;
                  $query = $query->onlyTrashed();
               }
         }
         //check if users brows select user type 0 or
         if($slug === 'users' || $slug === 'ip_white_list'){
               $query=$query->where('type', 0);
         }
         // If a column has a relationship associated with it, we do not want to show that field
         $this->removeRelationshipField($dataType, 'browse');

         // dd($request);
         // dd($search);
         if(count($search->value) != 0 && count($search->key) != 0 && count($search->value) != 0) {
               for($i = 0; $i < count($search->value); $i++) {
                  $type = $search->value[$i]['type'] ?? null;
                  if($type == 'relationship' && isset($search->value[$i]['details']) && (isset($search->value[$i]['value']) && $search->value[$i]['value'] != '__None__' && $search->key[$i] && $search->filter)){
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
                  } else if(gettype($search->value[$i]) != 'array' && $search->value[$i] != '__None__' && $search->key[$i] && $search->filter) {
                     $search_filter = ($search->filter == 'equals') ? '=' : 'LIKE';
                     $search_value = ($search->filter == 'equals') ? $search->value[$i] : '%'.$search->value[$i].'%';
                     $query->where($search->key[$i], $search_filter, $search_value);
                  }
               }
         }

         // after check auth
         $saved_query = $query;
         
         if ($orderBy && in_array($orderBy, $dataType->fields())) {
               $querySortOrder = (!empty($sortOrder)) ? $sortOrder : 'desc';
               $dataTypeContent = call_user_func([
                  $query->orderBy($orderBy, $querySortOrder),
                  $getter,
               ]);
         } elseif ($model->timestamps) {
               $dataTypeContent = call_user_func([$query->with('currency')->latest($model::CREATED_AT), $getter]);
               // dd($dataTypeContent);
         } else {
               $dataTypeContent = call_user_func([$query->orderBy($model->getKeyName(), 'DESC'), $getter]);
         }
         
         // Replace relationships' keys for labels and create READ links if a slug is provided.
         $dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType);
         
         //find the amount of success transactions
         $sub_total = null;
         $grand_total = null;
         $crc = null;
         if($slug == 'transactions') {
               $crc = 'USD';
               $sub_total = $dataTypeContent->where('status', 'SUCCESS')->sum('amount');
               // $saved_page = $request->page ?? null;
               if(!isset($request->data_filter) && isset($request->page) && $request->page != '1') {
                  $grand_total = json_decode(Cookie::get('grand_total'));
               } else {
                  Cookie::queue(Cookie::forget('grand_total'));
                  $grand_total = $saved_query->where('status', 'SUCCESS')->sum('amount');
                  Cookie::queue('grand_total',json_encode($grand_total));
               }
         }
      } else {
         // If Model doesn't exist, get data from table name
         $dataTypeContent = call_user_func([DB::table($dataType->name), $getter]);
         $model = false;
      }

      // Check if BREAD is Translatable
      $isModelTranslatable = is_bread_translatable($model);

      // Eagerload Relations
      $this->eagerLoadRelations($dataTypeContent, $dataType, 'browse', $isModelTranslatable);

      // Check if server side pagination is enabled
      $isServerSide = isset($dataType->server_side) && $dataType->server_side;

      // Check if a default search key is set
      $defaultSearchKey = $dataType->default_search_key ?? null;
      
      // Actions
      $actions = [];
      if (!empty($dataTypeContent->first())) {
         foreach (Voyager::actions() as $action) {
               $action = new $action($dataType, $dataTypeContent->first());

               if ($action->shouldActionDisplayOnDataType()) {
                  $actions[] = $action;
               }
         }
      }


      // Define showCheckboxColumn
      $showCheckboxColumn = false;
      if (Auth::user()->can('delete', app($dataType->model_name))) {
         $showCheckboxColumn = true;
      } else {
         foreach ($actions as $action) {
               if (method_exists($action, 'massAction')) {
                  $showCheckboxColumn = true;
               }
         }
      }

      // Define orderColumn
      $orderColumn = [];
      if ($orderBy) {
         $index = $dataType->browseRows->where('field', $orderBy)->keys()->first() + ($showCheckboxColumn ? 1 : 0);
         $orderColumn = [[$index, $sortOrder ?? 'desc']];
      }

      $view = 'voyager::bread.browse';

      if (view()->exists("voyager::$slug.browse")) {
         $view = "voyager::$slug.browse";
      }

      // clear cookie for show contact list in merchant view
      if($slug == 'merchant' || $slug == 'integration') {
         Cookie::queue(Cookie::forget('select_list'));
      }

      $page_list_of_data = true;

      //clera filter date in on dashboard
      Session::forget('date_from');
      Session::forget('date_to');

      if($dataType->model_name == "App\Models\Product"){
         $categories = [];
               if(auth()->user()->role_id != 1 && auth()->user()->merchant_id != 0 ){
                  $merchant_id = auth()->user()->merchant_id;
               }else{
                  $merchant_id = Session::get('merchant_id');
               }

               if($merchant_id){
                  $categories = Category::where([
                     'merchant_id' => $merchant_id,
                  ])->get();
               }

               return Voyager::view($view, compact(
                  'actions',
                  'dataType',
                  'dataTypeContent',
                  'isModelTranslatable',
                  'search',
                  'orderBy',
                  'orderColumn',
                  'sortOrder',
                  'searchNames',
                  'isServerSide',
                  'defaultSearchKey',
                  'usesSoftDeletes',
                  'showSoftDeleted',
                  'showCheckboxColumn',
                  'page_list_of_data',
                  'sub_total',
                  'grand_total',
                  'crc',
                  'categories'
               ));
      }
      return Voyager::view($view, compact(
         'actions',
         'dataType',
         'dataTypeContent',
         'isModelTranslatable',
         'search',
         'orderBy',
         'orderColumn',
         'sortOrder',
         'searchNames',
         'isServerSide',
         'defaultSearchKey',
         'usesSoftDeletes',
         'showSoftDeleted',
         'showCheckboxColumn',
         'page_list_of_data',
         'sub_total',
         'grand_total',
         'crc',
      ));

   }

}
