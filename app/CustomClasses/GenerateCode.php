<?php

namespace App\CustomClasses;

class GenerateCode {
    public function __construct(){
        
    }

    public function moduleCode($module_name){
        //generate code for module
        $code = $module_name.date('YmdHis').rand(10,10000);
        return $code;
    }
}