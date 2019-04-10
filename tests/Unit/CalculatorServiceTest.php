<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Services\CalculatorService;

class CalculatorServiceTest extends TestCase
{
    /**
     * 
     *
     * @return void
     */
    public function testCalcMortgageAndReturnsAsExpected()
    {
        $mortgage = CalculatorService::calcMortgage(100000,0.005,180,5000);
        $expectedMortgageValue = 833.7305461118808;

        $this->assertEquals($mortgage, $expectedMortgageValue);
    }

    /**
     * 
     *
     * @return void
     */
    public function testCalcMortgageAndReturnsInvalidValue()
    {
        $mortgate = CalculatorService::calcMortgage(100000,0.005,180,5000);
        $unexpectedMortgageValue = 900;

        $this->assertNotEquals($mortgate, $unexpectedMortgageValue);
    }
}
