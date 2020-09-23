<?php

namespace App\Message\Transaction\Model;


class TransactionListFilterBy
{
    private ?int $transactionTypeId;
    private ?int $lastDays;
    private ?bool $isIncome;

    public function __construct(?array $data = null)
    {
        $this->transactionTypeId = $data['transactionTypeId'] ?? null;
        $this->lastDays = $data['lastDays'] ?? null;
        $this->isIncome = $data['isIncome'] ?? null;
    }

    public function getTransactionTypeId(): ?int
    {
        return $this->transactionTypeId;
    }

    public function getLastDays(): ?int
    {
        return $this->lastDays;
    }

    public function isIncome(): ?bool
    {
        return $this->isIncome;
    }
}
