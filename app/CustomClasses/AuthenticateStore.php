<?php

namespace App\CustomClasses;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AuthenticateStore {
    use ApiResponser;

    public function __construct(){
        
    }

    public function _checkStoreToken($data){
        $result = false;
        if (isset($data)) {
            $store_type = 1;
            switch($data->store_type) {
                // Shopify
                case(1):
                    $storeToken = $this->validateXShopifyAccessToken($data);
                    $result = $storeToken;
                    break;
                // PrestaShop
                case(2):
                    $result = false;
                    break;
                // Magento 2
                case(3):
                    $storeToken = $this->getMagentoToken($data);
                    $result = $storeToken;
                    break;
                // Woo Commerce
                case(4):
                    $result = false;
                    break;
                // ...
                case(5):
                    $result = false;
                    break;
                default:
                    $result = false;
            }

            return $result;
        }
    }

    private function validateXShopifyAccessToken($request) {
        $synRequest = Http::withHeaders([$request->key[0] => $request->value[0]]);
        $response = $synRequest->get($request->store_enpoint.'products.json');
        if($response->status() == 200 ) {
            return ['status' => true];
        } else {
            return ['status' => false,'message' => json_decode($response)->errors];
        }
    }

    private function getMagentoToken($storeInfo) {
        $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post($storeInfo->store_enpoint.'integration/admin/token', [
                $storeInfo->key[0] => $storeInfo->value[0],
                $storeInfo->key[1] => $storeInfo->value[1],
            ]);
        $resultToken = json_decode($response,true);
        if(!is_array($resultToken)){
            return ['status' => true];
        } else {
            return ['status' => false,'message' => $resultToken['message']];
        }
    }
}