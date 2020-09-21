<?php


namespace App\Helper;


class TransactionSummaryCalculator
{
    private array $entries;

    public function __construct(array $entries)
    {
        $this->entries = $entries;
    }

    public function getSummaryInfo(): array
    {
        $totalAmount = $totalIncome = $totalDeductibleExpanses = 0;
        foreach ($this->entries as $datum) {
            $totalAmount += $datum['totalAmount'];
            $totalIncome += $datum['incomeAmount'] ?? 0;
            $totalDeductibleExpanses += $datum['deductibleExpanses'] ?? 0;
        }
        $totalOutcome = round($totalAmount - $totalIncome, 2);

        return [
            'totalOutcome' => $totalOutcome,
            'totalSummary' => round($totalIncome - $totalOutcome, 2),
            'totalIncome' => round($totalIncome, 2),
            'totalDeductibleExpanses' => round($totalDeductibleExpanses, 2)
        ];
    }
}
