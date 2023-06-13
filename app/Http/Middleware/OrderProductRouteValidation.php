<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderProductRouteValidation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(!Auth::check()){
            return abort(404);
        }else{
            if(Auth::user()->id != config('common.superadmin_user') && Auth::user()->role_id != config('common.superadmin_role')) {
                return $next($request);
            } else {
                return abort(404);
            }
        }
    }
}
