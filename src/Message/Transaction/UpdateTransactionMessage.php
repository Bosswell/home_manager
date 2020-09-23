<?php

namespace App\Message\Transaction;


class UpdateTransactionMessage
{
    private ?int $id;
    private float $amount;
    private ?int $transactionTypeId;
    private ?string $description;
    private bool $isIncome;
    private ?int $taxPercentage;

    public function __construct(?array $data = null)
    {
        $this->id = $data['id'] ?? null;
        $this->amount = $data['amount'] ?? 0;
        $this->transactionTypeId = $data['transactionTypeId'] ?? 0;
        $this->description = $data['description'] ?? null;
        $this->isIncome = $data['isIncome'] ?? false;
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = (int)$id;
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


    public function setTaxPercentage(?int $taxPercentage): void
    {
        $this->taxPercentage = $taxPercentage;
    }
}