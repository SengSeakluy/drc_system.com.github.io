<?php

/*
      |--------------------------------------------------------------------------
      | Default Value to use in the system
      |--------------------------------------------------------------------------
      */
return [

      /*
            |--------------------------------------------------------------------------
            | Declaration (table shipback) states
            |--------------------------------------------------------------------------
            |
            |
            */

      "declaration_state" => [
            "REGISTERING"     => 'registering',
            'REGISTERED'      => 'registered',
            "RESOLVED"        => 'resolved',
            "IDENTIFIED"      => 'identified',
            "NOT_ELLIGIBLE"   => 'not_elligible',
            "ARCHIVED"        => 'archived',
      ],

      /*
            |--------------------------------------------------------------------------
            | Shipback (table shipback_item) states
            |--------------------------------------------------------------------------
            |
            */

      "shipback_item_states" => [
            "REGISTERING" => 'registering',
            "PENDING_APPROVAL" => 'pending_approval',
            "APPROVED" => 'approved',
            "REJECTED" => 'rejected',
            "REGISTERED" => 'registered',
            "REFUND_ACCEPTED" => 'refund_accepted',
            "REFUND_REFUSED" => 'refund_refused',
            "EXCHANGE_ACCEPTED" => 'exchange_accepted',
            "EXCHANGE_REFUSED" => 'exchange_refused',
            "SPARE_PART_ACCEPTED" => 'spare_part_accepted',
            "SPARE_PART_REFUSED" => 'spare_part_refused',
            "RESOLVED" => 'resolved',
            "ARCHIVED" => 'archived',
            "NOT_ELLIGIBLE" => 'not_elligible',
      ],


      /*
      |--------------------------------------------------------------------------
      | Shipment states get from after ship
      |--------------------------------------------------------------------------
      |
      */

      "shipment_states_aftership" => [
            "REGISTERED" => 'Registered',
            "PENDING" => 'Pending',
            "INFORMATION_RECEIVED" => 'InfoReceived',
            "IN_TRANSIT" => 'InTransit',
            "OUT_FOR_DELIVERY" => 'OutForDelivery',
            "FAILED_ATTEMPT" => 'AttemptFail',
            "DELIVERED" => 'Delivered',
            "AVAILABLE_FOR_PICKUP" => 'AvailableForPickup',
            "EXCEPTION" => 'Exception',
            "EXPIRED" => 'Expired',
            "CLOSED" => 'Closed',
      ],
      /*
      |--------------------------------------------------------------------------
      | Shipment states 
      |--------------------------------------------------------------------------
      |
      */

      "shipment_states" => [
            "Registered" => 'registered',
            "Pending" => 'pending',
            'InfoReceived' => "information_received",
            "InTransit" => 'in_transit',
            "OutForDelivery" => 'out_for_delivery',
            "AttemptFail" => 'failed_attemt',
            "Delivered" => 'delivered',
            "AvailableForPickup" => 'available_for_pickup',
            "Exception" => 'exception',
            "Expired" => 'expired',
            "Closed" => 'closed',
      ],
      /*
            |--------------------------------------------------------------------------
            | color states
            |--------------------------------------------------------------------------
            |
            */
      "color_states" => [
            "created" => "#0038E5",
            "registering" => "#0571DC",
            "registered" => "#0BA3D3",
            "approved" => "#11cac7",
            "exchange_accepted" => "#17b963",
            "spare_part_accepted" => "#1db03a", 
            "exchange_refused" => "#ff8c00", 
            "pending_approval" => "#24a81e", 
            "rejected" => "#ff0000", 
            "resolved" => "#000", 
            "archived" => "#bababa", 
            "refund_accepted" => "#c423ed", 
            "refund_refused" => "#ffe60b", 
            "spare_part_refused" => "#ff006f"
      ],
      "KEY_MODULE" => [
            'RULE_FOR_PRICING' => "rule_for_pricing"
      ],
      "OPERATION_OF_RULE"     => [
            "AND" => 'and',
            "OR"  => 'or'
      ]
];
