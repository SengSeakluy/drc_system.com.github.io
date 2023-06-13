<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class CustomVoyagerAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $urlLogin = '/admin/signin/'.(Session::get('local') ?? 'en');

        auth()->setDefaultDriver(app('VoyagerGuard'));

        // convert datetime based on client_timezone
        if (isset(Auth::user()->timezone)) {
            Config::set('app.client_timezone', Auth::user()->timezone);
        }

        if (!Auth::guest()) {
            // if account is locked cannot access the system except super admin
            if(Auth::user()->id != config('common.superadmin_user') && Auth::user()->role_id != config('common.superadmin_role')){
                if(Auth::user()->is_locked>0){
                    Auth::logout();
                    Session::flush();
                    return redirect()->guest($urlLogin)->withErrors("Account is locked!");
                }
            }

            //set merchant id in session 
            if(Auth::user()->id == config('common.superadmin_user') && Auth::user()->role_id == config('common.superadmin_role')){
                $slug = self::getSlug($request);
                if(isset($slug) && in_array($slug,array('product_item_prices'))){
                    $result = DB::table($slug)->where('id',$request->id)->first();
                    if(isset($result->merchant_id) && $result->merchant_id && $request->id)
                        Session::put('merchant_id', $result->merchant_id);
                    else
                        Session::put('merchant_id', 0);
                }
            }

            $user = Auth::user();
            
            app()->setLocale($user->locale ?? app()->getLocale());

            return $user->hasPermission('browse_admin') ? $next($request) : redirect('/');
        }

        return redirect()->guest($urlLogin);
    }

    public function getSlug($request)
    {
        if (isset($this->slug)) {
            $slug = $this->slug;
        } else {
            $slug = explode('.', $request->route()->getName())[1];
        }

        return $slug;
    }
}
