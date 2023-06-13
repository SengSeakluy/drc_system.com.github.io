<?php

namespace App\CustomClasses;

use TCG\Voyager\Models\Role;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ValidateData {
    public function __construct(){
        
    }

    public function _checkRecordExistAndRecordOfMerchant($slug,$id){
        $data = array();
        if($id && $id>0){
            // check record is exist or not
            // $result = DB::table($slug)->where('id', $id)->first();
            $result  = app(Voyager::model('DataType')->where('slug', '=', $slug)->first()->model_name)::where('id', $id)->first();
            $redirect = redirect()->route("voyager.{$slug}.index");
            if(!$result){
                //INVALID RECORD ID
                $data = array(
                    'status' => 1,
                    'message' => 'INVALID RECORD ID',
                    'alert-type' => 'error',
                    'redirect' => $redirect
                );
            }else if(Auth::user()->id != config('common.superadmin_user') && Auth::user()->role_id != config('common.superadmin_role')){
                // check whether this record merchant belong to this user merchant
                $merchant_id = $result->merchant_id ?? $result->id;
                if (Auth::user()->merchant_id > 0) {
                    if($merchant_id != Auth::user()->merchant_id){
                        // $redirect = redirect()->route("voyager.{$slug}.index");
                        $data = array(
                            'status' => 2,
                            'message' => "This record doesn't belong to your merchant.",
                            'alert-type' => 'error',
                            'redirect' => $redirect
                        );
                    }
                } else {

                    // technical team validate data based on based on selected merchant 
                    $merchantList = [];
                    $roleInfo = Role::where('id', Auth::user()->role_id)->first();

                    // if has configure merchant filter data
                    if ($roleInfo->merchant_list) {
                        foreach ((array) json_decode($roleInfo->merchant_list) as $index_merchant => $merchant) {
                            $merchantList[] = $index_merchant;
                        }
                    
                        if (!in_array($slug,['category','company_hierarchies','contacts','currency','roles','payment_method'])) {
                            if (!in_array($merchant_id,$merchantList) && Auth::user()->id != $id) {
                                $data = array(
                                    'status' => 2,
                                    'message' => "This record doesn't belong to your merchant list.",
                                    'alert-type' => 'error',
                                    'redirect' => $redirect
                                );
                            }
                        }
                        
                        // if enable role merchant, show only selected merchant
                        if ($slug == 'merchant') {
                            if (!in_array($merchant_id,$merchantList)) {
                                $data = array(
                                    'status' => 2,
                                    'message' => "This record doesn't belong to your merchant list.",
                                    'alert-type' => 'error',
                                    'redirect' => $redirect
                                );
                            }
                        }
                    }
                }
            }
        }
        return $data;
    }
}