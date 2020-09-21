<?php

namespace Tests\Helper;

use App\Helper\TransactionSummaryCalculator;
use PHPUnit\Framework\TestCase;


class TransactionSummaryCalculatorTest extends TestCase
{
    private array $testCase;

    protected function setUp(): void
    {
        $this->testCase = [
            [
                'totalAmount' => 1000,
                'incomeAmount' => 200,
                'deductibleExpanses' => 300
            ],
            [
                'totalAmount' => 2000,
                'incomeAmount' => 400,
                'deductibleExpanses' => 600
            ]
        ];
    }

    public function testGetSummaryInfo()
    {
        $calculator = new TransactionSummaryCalculator($this->testCase);
        $info = $calculator->getSummaryInfo();

        $this->assertEquals(2400, $info['totalOutcome']);
        $this->assertEquals(-1800, $info['totalSummary']);
        $this->assertEquals(600, $info['totalIncome']);
        $this->assertEquals(900, $info['totalDeductibleExpanses']);
    }
}