<?php

namespace App\Services;
use Illuminate\Support\Facades\Validator;

class InputValidatorService {
    static function validateInput($downPayment, $principal, $data) {
        $minDownPayment = 0.05;
        $response = [];

        $rules = [
            "property_price" => 'required',
            "down_payment" => 'required | gt:0',
            "annual_interest_rate" => 'required',
            "amortization_period" => 'required',
            "payment_schedule" => 'required |in:"accelerated", "bi-weekly", "monthly"',
        ];

        $messages = [
            'required' => "The field :attribute is required",
            'in' => "Payment schedule should be 'accelerated', 'bi-weekly' or 'monthly'"
        ];

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            
            $fields = []; 
            foreach ($validator->messages()->toArray() as $key => $value) { 
                $obj = [];
                $obj['field'] = $key; 
                $obj['message'] = $value[0];
                
                $fields[] = $obj; 
            }


           $response["fields"] = $fields;
        }


        if ($downPayment < ($principal * $minDownPayment) && $principal < 1000000) {
            $response["messages"][] = "Down Payment has to be at least $" . $principal * $minDownPayment;
        }

        if ($principal >= 1000000 && $downPayment < ($principal * 0.2)) {
            $response["messages"][] = "Houses over 1 Million requires a 20% down payment. Required amount: $" . $principal * 0.2;
        }

        if ($principal <= $downPayment) {
            $response["messages"][] = "Property price Cannot be less than the Down Payment";
        }
         
        return $response;
    }
}