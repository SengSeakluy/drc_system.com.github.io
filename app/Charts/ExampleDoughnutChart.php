<?php

namespace App\Charts;

use Illuminate\Support\Facades\Auth;
use Chartisan\PHP\Chartisan;
use Illuminate\Http\Request;
use Fidum\ChartTile\Charts\Chart;
use Illuminate\Support\Facades\DB;

class ExampleDoughnutChart extends Chart
{
    public ?array $middlewares=['auth'];
    public function handler(Request $request): Chartisan
    {
        $data = [];
        $total_users = $total_roles = $total_positions =  0;
        $data = [$total_users, $total_roles, $total_positions];
        if(isset(Auth::user()->id)){
            if(Auth::user()->id == config('common.superadmin_user') && Auth::user()->role_id == config('common.superadmin_role')){
                $total_users = DB::table('users')->whereNull('deleted_at')->count();
                //$total_roles = DB::table('roles')->whereNull('deleted_at')->count();
                $total_positions = DB::table('company_hierarchies')->whereNull('deleted_at')->count();
                $data = [$total_users, $total_roles, $total_positions];
            }else{
                $total_users = DB::table('users')->where('merchant_id',Auth::user()->merchant_id)->whereNull('deleted_at')->count();
                //$total_roles = DB::table('roles')->where('merchant_id',Auth::user()->merchant_id)->whereNull('deleted_at')->count();
                $total_positions = DB::table('company_hierarchies')->whereNull('deleted_at')->count();
                $data = [$total_users, $total_roles, $total_positions];
            }
        }

        return Chartisan::build()
            ->labels(['Users', 'Positions'])
            ->dataset('Analytics', $data);
     
    }
    
    public function type(): string
    {
        return 'doughnut';
    }

    public function options(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => true,
            'legend' => [
                'display' => true,
                'position' => 'right',
            ],
            'scales' => [
                'xAxes' => ['display' => false],
                'yAxes' => ['display' => false],
            ],
        ];
    }

    public function colors(): array
    {
        return [[['#FF9CEE'], ['#B28DFF'], ['#6EB5FF']]];
    }
}
