<?php

namespace App\Http\Controllers\Admin\Voyager;

use ReflectionClass;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use TCG\Voyager\Models\Role;
use Illuminate\Support\Carbon;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Support\Facades\DB;
use App\Exports\SystemReportExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use TCG\Voyager\Database\Types\Type;
use Illuminate\Support\Facades\Schema;
use TCG\Voyager\Database\Schema\Table;
use TCG\Voyager\Database\Schema\SchemaManager;
use App\Http\Controllers\admin\CustomVoyagerBaseController;
use TCG\Voyager\Http\Controllers\Traits\BreadRelationshipParser;

class ExportReportController extends CustomVoyagerBaseController
{
    public function exportToExcel(Request $request){
        $temporaryTable = $request->table;
        // // apply filter
        $dataFields = [];
        $dataFieldsType = DB::select("SHOW COLUMNS FROM ". $temporaryTable);
        foreach ($dataFieldsType as $field)
            $dataFields[] = $field->Field;

        $header = $dataFields;
        $headers = [];
        foreach ($header as $item)
            $headers[] = strtoupper($item);

        $arrayFilter = $this->filterData($dataFieldsType, $dataFields, $request, $newFilterObject, $selectDateExpression);

        $dataContents = DB::table($temporaryTable)->where($arrayFilter)->get()->toArray();

        // check whether the $slug has column merchant_id
        if(Auth::user()->id != config('common.superadmin_user') && Auth::user()->role_id != config('common.superadmin_role')){
            if(Schema::hasColumn($temporaryTable, 'merchant_id') && Auth::user()->merchant_id>0){
                $dataContents = DB::table($temporaryTable)
                ->where('merchant_id', '=', Auth::user()->merchant_id)
                ->where($arrayFilter)
                ->get()->toArray();
            } else {
                // filter data based on selected merchant
                $merchantList = [];
                $roleInfo = Role::where('id', Auth::user()->role_id)->first();
                        
                // if has configure merchant filter data
                if ($roleInfo->merchant_list) {
                    foreach ((array) json_decode($roleInfo->merchant_list) as $index_merchant => $merchant) {
                        $merchantList[] = $index_merchant;
                    }
        
                    if (Schema::hasColumn($temporaryTable, 'merchant_id')) {
                        $dataContents = DB::table($temporaryTable)
                        ->where($arrayFilter)
                        ->whereIn('merchant_id', $merchantList)
                        ->get()->toArray();
                    }
                }
            }
        }
        
        return Excel::download(new SystemReportExport($headers, $dataContents, $temporaryTable), ucfirst(strtoupper($temporaryTable).'_'.now()).'.xlsx');
    }

    public function filterData($dataFieldsType, $dataFields, $request, &$newFilterObject, &$selectDateExpression)
    {
        $filterObject = [];
        foreach($dataFields as $filter){
            $filterObject[$filter] = $request->$filter;
        }

        // some filter parameters need to convert date
        $needDateConversion = []; 
        $selectDateExpression = [];
        foreach ($dataFieldsType as $key => $fieldsType) {
            if (strpos($fieldsType->Type, 'datetime') !== false || strpos($fieldsType->Type, 'timestamp') !== false || strpos($fieldsType->Type, 'date') !== false ) {
                $needDateConversion[$fieldsType->Field] = $fieldsType->Field;
            }
        }
        
        $newFilterObject = array_filter($filterObject);
        $arrayFilter = [];
        foreach ($newFilterObject as $key => $data) {
            if (isset($needDateConversion[$key]) && $needDateConversion[$key] == $key) {
                $dateExpression = $key.'_type';
                $dateExpressionVal = $request->$dateExpression;
                $selectDateExpression[$key.'_type'] = $dateExpressionVal;
                // $dateConversion = Carbon::parse($data, Auth::user()->timezone)->setTimezone(config('app.timezone'))->toDateTimeString();
                
                if ($dateExpressionVal == "BETWEEN") {
                    $request_to_date = $key.'_to';
                    $from_date = $data;
                    $to_date = $request->$request_to_date;
                    $selectDateExpression[$key.'_to'] = $to_date;
                    $arrayFilter[] = [DB::raw("DATE(".$key.")"), ">=",  $from_date];
                    $arrayFilter[] = [DB::raw("DATE(".$key.")"), "<=",  $to_date];
                } else {
                    $arrayFilter[] = [DB::raw("DATE(".$key.")"), $dateExpressionVal, $data];
                }
            }else{
                $arrayFilter[] = [$key, 'LIKE', '%'.$data.'%'];
            }
        }

        return $arrayFilter;
    }
}
