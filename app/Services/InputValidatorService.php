<?php

namespace App\Services;
use Illuminate\Support\Facades\Validator;

class InputValidatorService {
    static function validateInput($downPayment, $principal, $data) {
        $minDownPayment = 0.05;

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
            return response($validator->errors()->first(), 400);
        }


        if ($downPayment < $principal * $minDownPayment && $principal < 1000000) {
            return "Down Payment has to be at least $" . $principal * $minDownPayment;
        }

        if ($principal >= 1000000 && $downPayment < $principal * 0.2) {
            return "Houses over 1 Million requires a 20% down payment. Required amout: $" . $principal * $minDownPayment;
        }

        if ($principal <= $downPayment) {
            return "Principal Cannot be less than the Down Payment";
        }
         
        return null;
    }
}