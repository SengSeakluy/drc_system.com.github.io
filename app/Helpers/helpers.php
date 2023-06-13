<?php

use Carbon\Carbon;
use App\Models\ApiLog;
use App\Models\WebhookActivity;
use App\Models\MerchantStoreToken;
use Illuminate\Support\Facades\Log;
use App\Models\ScheduleSynchronizeLog;

/**
 * 
 * webhook and consumer page api log
 * log to /Storage/Consumer-api-log
 */

function apiLog(string $message) {
    Log::channel('returnlog')->info($message);
}

/**
 * Write code on Method
 *
 * @return response()
 */
if (! function_exists('convertYmdToMdy')) {
    function convertYmdToMdy($date)
    {
        return Carbon::createFromFormat('Y-m-d', $date)->format('m-d-Y');
    }
}
  
/**
 * Write code on Method
 *
 * @return response()
 */
if (! function_exists('convertMdyToYmd')) {
    function convertMdyToYmd($date)
    {
        return Carbon::createFromFormat('m-d-Y', $date)->format('Y-m-d');
    }
}
/**
 * get time from datetime 2022-11-03 09:20:27 to  09:20 AM

 *
 * @return response()
 */
if (! function_exists('datetimeToTime')) {
    function datetimeToTime($date)
    {
        return Carbon::parse($date)->rawFormat('g:i A');
    }
}
/**
 * get time from datetime 2022-11-03 09:20:27 to  default Nov 3, 2022

 *
 * @return response()
 */
if (! function_exists('formatDatetime')) {
    function formatDatetime($date,$format='M j, Y g:i A')
    {
        return Carbon::parse($date)->format($format);
    }
}

/**
 * get time from datetime 2022-11-03 09:20:27 to  default Nov 3, 2022

 *
 * @return response()
 */
if (! function_exists('datetimeToDate')) {
    function datetimeToDate($date)
    {
        return Carbon::parse($date)->rawFormat('M j, Y');
    }
}

/**
 * get one of carrier without rates
 * 
 */
function getOneCarrierWithoutRates() {
    dd('test');
}

/**
 * full name
 * 
 */
function fullname($firstname , $lastname) {
     return $firstname.' '.$lastname;
}
/**
 * shipment status from after ship
 * @param string $statusCode status to mapping
 * 
 * @return string status readable
 */
function shipmentState($statusCode) {
    
    $allStatus = [        
            'InfoReceived' => 'Information Received',//Carrier has received request from shipper and is about to pick up the shipment.
            'InTransit' => 'In Transit',//Carrier has accepted or picked up shipment from shipper. The shipment is on the wa
            'OutForDelivery' => 'Out for Delivery',//Carrier is about to deliver the shipment , or it is ready to pickup.
            'AttemptFail' => 'Failed Attempt',//	Carrier attempted to deliver but failed, and usually leaves a notice and will try to deliver again.
            'Delivered' => 'Delivered',//The shipment was delivered successfully.
            'AvailableForPickup' => 'Available for Pickup',//The package arrived at a pickup point near you and is available for pickup.
            'Exception' => 'Exception',//Custom hold, undelivered, returned shipment to sender or any shipping exceptions.
            'Expired' => 'Expired',//Shipment has no tracking information for 30 days since added.
            'Pending' => 'Pending',//New shipments added that are pending to track, or new shipments without tracking information available yet.
            'Closed' => 'Closed',//shoprun back status
        ];

    if(!in_array($statusCode,$allStatus))
    {
        return 'Unkown status';
    }

    return $allStatus[$statusCode];
}

/**
 * 
 * webhook log activity
 * @param string $payload payload get from or send to
 * @param string $type receive or send
 * @param string $status payload receive or send
 * @param string $message message if error
 * @param string $terminal shipup or afteship or scanapp
 *  
 */
 
if (! function_exists('webhookLog')) {
    function webhookLog($payload,$type,$message,$terminal,$status=400)
    {
        try {
            
            if (in_array(null ,[$payload,$type,$status,$message,$terminal]))
            {
                apiLog("==============".json_encode([$payload,$type,$status,$message,$terminal])."=============");
            }
            $json = json_decode($payload,true);

            $resutl = WebhookActivity::create([
                        'payload'   => $payload,
                        'type'      => $type,
                        'status'    => $status,
                        'message'   => $message,
                        'terminal'  => $terminal,
                        'brand_id'  => isset($json['msg']['custom_fields']['brand_id']) ? $json['msg']['custom_fields']['brand_id'] : 0,
                        'merchant_id'  => isset($json['msg']['custom_fields']['merchant_id']) ? $json['msg']['custom_fields']['merchant_id']:0
                    ]);
            apiLog("==============$resutl=============");
        } catch (\Throwable $th) {
            apiLog("===========".$th->getMessage()."================");
            
        }
    }
}

/**
 * 
 * get all merchant token by merchant id
 * 
 */
if (! function_exists('getStoreMerchantToken')) {
    function getStoreMerchantToken($merchantId) {
        $allToken = MerchantStoreToken::where('merchant_id' ,$merchantId)->get();
        
        if (isset($allToken)) {
            return $allToken->toArray();
        }

        return [];
    }
}

/**
 * 
 * create api log to database
 * @param string $uuid 
 * @param string $merchantId 
 * @param string $method 
 * @param string $url 
 * @param string $status 
 * @param string $message 
 * @param datetime $createdAt 
 *  
 */
 
if (! function_exists('srb_apiLog')) {
    function srb_apiLog($uuid,$merchantId,$method,$url,$status,$message,$createdAt)
    {
        try {
            
            $resutl = ApiLog::create([
                'uuid'           => (string) $uuid,
                'merchant_id'    => $merchantId,
                'method'         => $method,
                'endpoint'       => $url,
                'status'         => $status,
                'message'        => $message,
                'created_at'     => $createdAt
            ]);
            apiLog("==============$resutl=============");
        } catch (\Throwable $th) {
            apiLog("===========".$th->getMessage()."================");  
        }
    }
}


if (! function_exists('scheduleLog')) {
    function scheduleLog($data) {
        try {
            ScheduleSynchronizeLog::create([
                'full_endpoint' => $data['full_endpoint'],  
                'sync_at'       => $data['sync_at'],
                'store_type'    => $data['store_type' ],
                'type'          => $data['type'],
                'status'        => $data['status'],
                'merchant_id'   => $data['merchant_id'],
                'brand_id'      => $data['brand_id']
            ]);
        } catch (\Throwable $th) {
            
        }

    }
}

/**
 * 
 * create log when sync data 
 * 
 * 
 */
if (! function_exists('syncApiLog')) {
    function syncApiLog(array $data) {
        try {
            $return =  ApiLog::create([
                    'uuid'           => $data[0],
                    'merchant_id'    => $data[1],
                    'method'         => $data[2],
                    'endpoint'       => $data[3],
                    'status'         => $data[4],
                    'message'        => $data[5],
                    'created_at'     => Carbon::now()
                    ]);

        } catch (\Throwable $th) {
        }

    }
}