<?php

namespace App\Http\Middleware;

use Closure;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LangMiddleware
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
        if(is_numeric($request->route('lang'))){
            echo exit($this->errorResponse('Invalid Language.',Response::HTTP_BAD_REQUEST));
        }
        if(!in_array($request->route('lang'),$this->scanLanguages())){
            echo exit($this->errorResponse('Language Not Supported.',Response::HTTP_BAD_REQUEST));
        }
        return $next($request);
    }

    // check all languages supported by the application.
    private function scanLanguages(): array
    {
        $filtered = ['.', '..'];

        $dirs = [];
        $d = dir(resource_path('lang'));
        while (($entry = $d->read()) !== false) {
            if (is_dir(resource_path('lang').'/'.$entry) && !in_array($entry, $filtered)) {
                $dirs[] = $entry;
            }
        }

        return $dirs;
    }
}
