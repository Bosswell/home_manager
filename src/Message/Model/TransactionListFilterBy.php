<?php

namespace App\Message\Model;


class TransactionListFilterBy
{
    private ?int $transactionTypeId;
    private ?int $lastDays;

    public function __construct(?array $data = null)
    {
        $this->transactionTypeId = $data['transactionTypeId'] ?? null;
        $this->lastDays = $data['lastDays'] ?? null;
    }

    public function getTransactionTypeId(): ?int
    {
        return $this->transactionTypeId;
    }

    public function getLastDays(): ?int
    {
        return $this->lastDays;
    }
}
