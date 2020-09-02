<?php

namespace App\Message;


final class CreateTransactionMessage
{
    private float $amount;
    private int $transactionTypeId;
    private ?string $description;

    public function __construct(?array $data = null)
    {
        $this->amount = $data['amount'] ?? 0;
        $this->transactionTypeId = $data['transactionTypeId'] ?? 0;
        $this->description = $data['description'] ?? null;
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

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = (string)$description;
    }
}