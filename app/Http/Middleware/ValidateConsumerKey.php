<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Carbon\Carbon;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Crypt;
use Laravel\Sanctum\PersonalAccessToken;

class ValidateConsumerKey
{
    use ApiResponser;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->hasHeader('consumer-key'))
        {
            try {

                try {
                    $result = Crypt::decrypt($request->header('consumer-key'));
                } catch (\Throwable $th) {
                    return $this->error([],'Unauthorized',Response::HTTP_UNAUTHORIZED);
                }
                $token  = PersonalAccessToken::findToken($result['token']);
                if(!$token) 
                {
                    throw new Exception('Unauthorized',Response::HTTP_UNAUTHORIZED);
                }
                
                if ($token->expires_at && $token->created_at->lte(now()->subMinute(config('sanctum.expiration'))))
                {
                    throw new Exception('Unauthorized',Response::HTTP_UNAUTHORIZED);
                }

                $userData = $token->tokenable; 
                
                if (!$userData) 
                {
                    throw new Exception('Unauthorized',Response::HTTP_UNAUTHORIZED);
                }

                return $next($request);
            } catch (\Throwable $e) {
                return $this->error([],$e->getMessage(),$e->getCode());
            }
        }
        return $this->error([],'Unauthorized',Response::HTTP_UNAUTHORIZED);
    }
}
