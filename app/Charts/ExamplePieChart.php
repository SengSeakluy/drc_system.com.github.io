<?php

namespace App\Charts;

use Illuminate\Support\Facades\Auth;
use Chartisan\PHP\Chartisan;
use Illuminate\Http\Request;
use Fidum\ChartTile\Charts\Chart;
use Illuminate\Support\Facades\DB;

class ExamplePieChart extends Chart
{
    public ?array $middlewares=['auth'];
    public function handler(Request $request): Chartisan
    {
        $data = [];
        $total_merchant = $total_contact = $total_product = $total_category = 0;
        $data = [$total_merchant, $total_contact, $total_product, $total_category];
        if(isset(Auth::user()->id)){
            if(Auth::user()->id == config('common.superadmin_user') && Auth::user()->role_id == config('common.superadmin_role')){
                $total_merchant = DB::table('merchant')->whereNull('deleted_at')->count();
                $total_contact = DB::table('contacts')->whereNull('deleted_at')->count();
                $total_product = DB::table('products')->whereNull('deleted_at')->count();
                $total_category = DB::table('category')->whereNull('deleted_at')->count();
                $data = [$total_merchant, $total_contact, $total_category, $total_product];
                return Chartisan::build()
                        ->labels(['Merchants', 'Contacts', 'Categories', 'Products'])
                        ->dataset('Analytics', $data);
            }else{
                $total_contact = DB::table('contacts')->where('merchant_id',Auth::user()->merchant_id)->whereNull('deleted_at')->count();
                $total_product = DB::table('products')->where('merchant_id',Auth::user()->merchant_id)->whereNull('deleted_at')->count();
                $total_category = DB::table('category')->where('merchant_id',Auth::user()->merchant_id)->whereNull('deleted_at')->count();
                $data = [$total_contact, $total_category, $total_product];
                return Chartisan::build()
                        ->labels(['Contacts', 'Categories', 'Products'])
                        ->dataset('Analytics', $data);
            }
        }else{
            return Chartisan::build()
                ->labels(['Merchants', 'Contacts', 'Categories', 'Products'])
                ->dataset('Analytics', $data);
        }
        
    }

    public function type(): string
    {
        return 'pie';
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
        if(isset(Auth::user()->id)){
            if(Auth::user()->id == config('common.superadmin_user') && Auth::user()->role_id == config('common.superadmin_role')){
                return [[['#036294'], ['#01aa3e'], ['#2798bf'], ['#b8bc2e']]];
            }else{
                return [[['#01aa3e'], ['#2798bf'], ['#b8bc2e']]];
            }
        }else{
            return [[['#036294'], ['#01aa3e'], ['#2798bf'], ['#b8bc2e']]];
        }
    }
}
