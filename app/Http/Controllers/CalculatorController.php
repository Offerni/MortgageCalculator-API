<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CalculatorService;
use App\Services\InputValidatorService;;


class CalculatorController extends Controller {

    /**
     * Change the number of year payments based on the type of schedule
     */

    private function getNumberOfYearPaymentsBySchedule($schedule) {
        $possibleSchedules = [
            "accelerated" => 26,
            "bi-weekly" => 24, 
            "monthly" => 12
        ];

        //if the type of schedule is valid, return number of possible schedules for the year
        if (array_key_exists($schedule, $possibleSchedules)) {
            return $possibleSchedules[$schedule];
        }

        return null;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        $data = $request->all();
        /* All the User inputs */
        $propertyPrice = $request->input('property_price');
        $downPayment = $request->input('down_payment');
        $annualInterestRate = $request->input('annual_interest_rate');
        $paymentSchedule = $request->input('payment_schedule');
        $amortizationPeriod = $request->input('amortization_period');

        $validateInputMessage = InputValidatorService::validateInput($downPayment, $propertyPrice, $data);
        
        if ($validateInputMessage) {
            $response = ["error" => $validateInputMessage];
            return response($response, 400)
                ->header('Content-Type', 'application/json');
        }

        $numberOfYearPaymentsBySchedule = $this->getNumberOfYearPaymentsBySchedule($paymentSchedule);

        $interestRate = ($annualInterestRate / 100) / $numberOfYearPaymentsBySchedule;
        $numberOfPayments = $amortizationPeriod * $numberOfYearPaymentsBySchedule;

        $payments = [];
        $payment = round(CalculatorService::calcMortgage($propertyPrice, $interestRate, $numberOfPayments, $downPayment), 2);
        $payments = ["payment_schedule_type" => "$paymentSchedule"];
        
        for ($i = 1; $i <= $numberOfPayments; $i++) {
            $payments["payment_schedule"]["$i"] = $payment;
        }
        
        return response($payments, 200)
                ->header('Content-Type', 'application/json');
    }
}
