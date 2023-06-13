<?php

namespace App\CustomClasses;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class ChangeAppLanguage {
    public function __construct(){

    }

    public static function setLocale($locale){
        if (!in_array($locale, ['en', 'es', 'fr', 'cn'])) {
            abort(400);
        }
        App::setLocale($locale);
    }
}