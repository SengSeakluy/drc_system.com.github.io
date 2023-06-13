<?php

namespace App\Http\Controllers\Admin;

use PDF;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use TCG\Voyager\Models\Role;
use App\Charts\UserLineChart;
use App\Charts\DailyUserRegister;
use App\Exports\TotalRecordExport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PreviousRecordExport;
use App\Exports\TopFiveMerchantsExport;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class DashboardFilterController extends Controller
{
    //fitler Top five merchant by date
    public function filterDataDashBoard( Request $request){
      
            try {
                //check if user fill end date but don't fill start date
                if( $request->date_from ==''){
                    $validator = Validator::make($request->all(), [
                        'date_from' => 'required',
                    ]);
                 
                    if ($validator) {
                        return response()->json(['success'=>'Error.','code'=>'400']);
                    }
                }
                if( $request->date_to ==''){
                    $validator = Validator::make($request->all(), [
                        'date_to' => 'required',
                    ]);
                 
                    if ($validator) {
                        return response()->json(['success'=>'Error.','code'=>'401']);
                    }
                }
                $from  =  isset($request->date_from) ? Carbon::parse( $request->date_from , Auth::user()->timezone)->setTimezone(config('app.timezone'))->toDateTimeString() : '';
                $to = isset($request->date_to) ? Carbon::parse( $request->date_to , Auth::user()->timezone)->setTimezone(config('app.timezone'))->addDay()->toDateTimeString() : Carbon::now()->setTimezone(config('app.timezone'))->toDateTimeString();
               
                Session::put('date_from',$from);
                Session::put('date_to',$to);

                Session::put('user_id',auth()->user()->id);
               
                $data['message'] = 'success';
                $data['code'] = 200;
                return json_encode($data);

            } catch (\Throwable $th) {
                    return json_encode('error');
            }
    }

    //refresh date filter 
    public function clearSessionKey(  Request $request )
    {
       
        Session::forget($request->date_from);
        Session::forget('date_to');

        $data['message'] = 'success';
        $data['code'] = 200;
        return json_encode($data);
    }
    
    public function sort($date,$data){
        $alldate = collect(range(1 , 30))->map(function ($days) use ($date) {
            return [
                'date' => $date->clone()->addDays($days)->toDateString(),
            ];
        })->pluck('date')->toArray();

        foreach($alldate as $key=>$date){
            if(!isset($data[$date])){
                $customers_data[$key]['no']=$key+1;
                $customers_data[$key]['total']='0';
                $customers_data[$key]['date']=$date;
            } else {
                $customers_data[$key]['no']=$key+1;
                $customers_data[$key]['total']=$data[$date];
                $customers_data[$key]['date']=$date;
            }
        }

        return $customers_data;
    }

    public function UserDailyRegister(Request $request){
    
            $year = $request->has('year') ? $request->year : date('Y');
            $users = User::select(DB::raw("COUNT(*) as count"))
                        ->whereYear('created_at', $year)
                        ->groupBy(DB::raw("Month(created_at)"))
                        ->pluck('count');
      
            $chart = new DailyUserRegister();
            $chart->dataset('User Daily Register', 'bar',['12','56','8','54','12','56','8','54','12','56','8','54','154','12','56','8','54','12','56','8','54'])->options([
                        'fill' => 'true',
                        'borderColor' => '#51C1C0',
                        'backgroundColor' => 'yellow'
                    ]);
      
            return $chart->api();
    }
}
