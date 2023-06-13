<?php

namespace App\Http\Controllers\Admin\Voyager;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Validator;

class CashierOrderController extends Controller
{
	public function index(){
	
		return view('vendor.voyager.cashier_order.browse');
	}

		
}
