<?php

namespace App\Http\Middleware;

use Closure;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\CustomClasses\ValidateRequest;

class ValidateApiInformation
{
    use ApiResponser;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $validate = new ValidateRequest();
        $response = $validate->_checkHeader($request);
        if(isset($response)) {
            return $response;
        } else {
            $next($request);
        }
    }
}
