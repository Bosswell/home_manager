<?php

namespace App\Message\Transaction;


class CreateTransactionMessage
{
    protected float $amount;
    protected ?int $transactionTypeId;
    protected ?string $description;
    protected bool $isIncome;
    protected ?int $taxPercentage;

    public function __construct(?array $data = null)
    {
        $this->amount = $data['amount'] ?? 0;
        $this->transactionTypeId = $data['transactionTypeId'] ?? 0;
        $this->isIncome = $data['isIncome'] ?? false;
        $this->description = $data['description'] ?? null;
        $this->taxPercentage = $data['taxPercentage'] ?? null;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getTransactionTypeId(): int
    {
        return $this->transactionTypeId;
    }

    public function setAmount($amount): void
    {
        $this->amount = (float)$amount;
    }

    public function setTransactionTypeId($transactionTypeId): void
    {
        $this->transactionTypeId = (int)$transactionTypeId;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription($description): void
    {
        $this->description = (string)$description;
    }

    public function setIsIncome(bool $isIncome): void
    {
        $this->isIncome = $isIncome;
    }

    public function isIncome(): bool
    {
        return $this->isIncome;
    }

    public function getTaxPercentage(): ?int
    {
        return $this->taxPercentage;
    }

    public function setTaxPercentage($taxPercentage): void
    {
        $this->taxPercentage = (int)$taxPercentage;
    }
}