<?php

namespace App\CustomClasses;

use App\Models\Customer;
use App\Models\Shipback;
use App\Models\ShipbackItem;
use App\Models\ReturnSetting;
use App\Models\CustomerFixFee;
use App\Models\CustomerItemFee;
use App\Models\CustomerMarginFee;
use Exception;

class CalculateConsumerFee {
    public function __construct(){

    }

    /**
     * calculate rates
     * @param integer $merchant_id 
     * @param string $ccy  example 'USD' of order
     * @param string $country_code 'FR' of order
     * @param string $warehouse_country_code 'FR' of order
     * @param integer $total_items_weight 
     * 
     * @return float 
     */
    public static function getRate($merchant_id, $ccy,$warehouse_country_code,$country_code, $total_items_weight) {
        try {
        
            $rate = 0;
            $consumerFixFee = $consumerMarginFee = $consumerItemFee = 0;
            // get fix fee
            $consumerFixFeeValue    = CustomerFixFee::where('merchant_id', $merchant_id)
                                        ->where('ccy', $ccy)
                                        ->select('value')
                                        ->first();
            // get margin fee
            $consumerMarginFeeValue = CustomerMarginFee::where('origin_country', $country_code)
                                        ->where('destination_country', $warehouse_country_code)
                                        ->select('value')
                                        ->first();
            // get item fee
            $consumerItemFeeValue   = CustomerItemFee::where('maximum_limit','>=',$total_items_weight)
                                        ->where('maximum_limit','<=',$total_items_weight)
                                        ->where('ccy' ,$ccy)
                                        ->select('value')
                                        ->first();
            
            if (isset($consumerFixFeeValue) && $consumerFixFeeValue['value'] > 0)
                $consumerFixFee = $consumerFixFeeValue['value'];

            if (isset($consumerMarginFeeValue) && $consumerMarginFeeValue['value'] > 0)
                $consumerMarginFee = ($consumerMarginFeeValue['value']/100)*$consumerFixFee;
            
            if (isset($consumerItemFeeValue) && $consumerItemFeeValue['value'] > 0)
                $consumerItemFee = $consumerItemFeeValue['value'];

            $rate = $consumerFixFee + $consumerMarginFee + $consumerItemFee;

            return $rate;

        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * change pricing rule of return 
     * @param integer $id id of merchant
     * @param integer $consmerId id of consumer
     * @param integer $shipbackId id of shipback
     * @param string $mode id of mode
     * 
     * @return boolean
     */
    
    public function ruleOfPricing($modeId,$consmerId , $id ,$shipbackId) {

        try {
        
            $shipItemsArr           = ShipbackItem::where('shipback_id' ,$shipbackId)
                                        ->pluck('reason_id')
                                        ->toArray();

            $consumer               = Customer::where('uuid' , $consmerId)->first();
            $consumerAddressInfo    = json_decode($consumer->address_info,true);
            $consumerCountryCode    = $consumer->country_code ?? $consumerAddressInfo['countryCode'];

            $rules      = ReturnSetting::where('merchant_id',$id)
                            ->where('key_module', config('states.KEY_MODULE.RULE_FOR_PRICING'))
                            ->first();

            if (!isset($rules)) {
                return false;
            }

            $values     = json_decode($rules->key_value , true);
            
            foreach($values as $value) {

                $checkCustomerCountryCode   = ($value['customer_country_code'] == $consumerCountryCode);
                $findReason                 = in_array($value['shipback_reason'] , $shipItemsArr);
                $checkMode                  = ($value['shipback_mode'] == $modeId );

                if ( $this->condition( $checkCustomerCountryCode, $findReason , $checkMode , $value['condition1'],$value['condition2']) ) 
                {
                return $value['shipback_status'];
                }
            }

            return false;
           
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    protected function condition($v1 ,$v2 ,$v3 ,$op1 ,$op2) {
        $and    = config('states.OPERATION_OF_RULE.AND');
        $or     = config('states.OPERATION_OF_RULE.OR');
        switch ($op1) {
            case $and: 
                switch ($op2) {
                    case $and: return ($v1 && $v2 && $v3 );
                    case $or : return ($v1 && $v2 || $v3 );
                };
            case $or : 
                switch ($op2) {
                    case $and: return ($v1 || $v2 && $v3 );
                    case $or : return ($v1 || $v2 || $v3 );
                };
            default: throw new Exception();
        }
    }

    /**
     * rule for item's refund
     * @param integer $id id of merchant
     * @param integer $consmerId id of consumer
     * @param integer $shipbackId id of shipback
     * 
     * @return boolean
     */
    public function checkRuleForItemRefund() {
        
    }

    public function ruleForRelocation() {

    }

    public function ruleForItemRelocation() {

    }

    public function ruleForItemAbandon() {

    }

    public function ruleForSparePart() {

    }
}

?>
