<?php

namespace App\Message\Transaction;


class CreateTransactionMessage
{
    protected float $amount = 0;
    protected ?int $transactionTypeId = null;
    protected ?string $description = null;
    protected bool $isIncome = false;
    protected ?int $taxPercentage = null;

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