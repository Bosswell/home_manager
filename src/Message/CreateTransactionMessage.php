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

    /**
     * @param mixed $amount
     */
    public function setAmount($amount): void
    {
        $this->amount = (float)$amount;
    }

    /**
     * @param mixed $transactionTypeId
     */
    public function setTransactionTypeId($transactionTypeId): void
    {
        $this->transactionTypeId = (int)$transactionTypeId;
    }
}