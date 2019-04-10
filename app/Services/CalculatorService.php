<?php

namespace App\Services;

class CalculatorService {

    /**
     * New principal based on CMHC Insurance Rules
     */
    static function calculateNewPrincipal($downPayment, $propertyPrice) {
        
        switch((float)$downPayment){
            //apply 4% when Down Payment is between 5% and 9.99%
            case $downPayment >= $propertyPrice*0.05 && $downPayment < $propertyPrice*0.1: 
                $insuranceRate = 0.04;
                $newPrincipal = ($propertyPrice-$downPayment) * $insuranceRate + ($propertyPrice-$downPayment);
                return $newPrincipal;

            //apply 3.10% when Down Payment is between 10% and 14.99%
            case $downPayment >= $propertyPrice*0.1 && $downPayment < $propertyPrice*0.15:
                $insuranceRate = 0.031;
                $newPrincipal = ($propertyPrice-$downPayment) * $insuranceRate + ($propertyPrice-$downPayment);
                return $newPrincipal;
            
            //apply 2.8% when Down Payment is between 15% and 19.99%
            case $downPayment >= $propertyPrice*0.15 && $downPayment < $propertyPrice*0.2:
                $insuranceRate = 0.028;
                $newPrincipal = ($propertyPrice-$downPayment) * $insuranceRate + ($propertyPrice-$downPayment);
                return $newPrincipal;    

            //Down payment over 20% doesn't need CMHC insurance, so its only Principal - downPayment
            case $downPayment >= $propertyPrice*0.2:
                $newPrincipal = $propertyPrice-$downPayment;
                return $newPrincipal;                            
            
        }
    }

    /**
     * Calculate the Mortgage after CMHC Insurance Rules.
     */
    static function calcMortgage(float $propertyPrice, float $interestRate, int $numberOfPayments, float $downPayment) {
        
        $newPrincipal = self::calculateNewPrincipal($downPayment, $propertyPrice);
        $montlyPayment = $newPrincipal * ($interestRate*pow(1+$interestRate, $numberOfPayments) / (pow(1+$interestRate, $numberOfPayments)-1));
        return $montlyPayment;
    }

}