<?php

namespace App\Message;


final class CreateTransactionMessage
{
    private float $amount;
    private int $transactionTypeId;

    public function __construct(?array $data = null)
    {
        $this->amount = $data['amount'] ?? 0;
        $this->transactionTypeId = $data['transactionTypeId'] ?? 0;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getTransactionTypeId(): int
    {
        return $this->transactionTypeId;
    }
}