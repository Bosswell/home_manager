<?php

namespace App\Message;


class UpdateTransactionMessage
{
    private ?int $id;
    private float $amount;
    private ?int $transactionTypeId;
    private ?string $description;
    private bool $isIncome;

    public function __construct(?array $data = null)
    {
        $this->id = $data['id'] ?? null;
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = (int)$id;
    }

    /**
     * @param bool $isIncome
     */
    public function setIsIncome(bool $isIncome): void
    {
        $this->isIncome = $isIncome;
    }

    /**
     * @return bool
     */
    public function isIncome(): bool
    {
        return $this->isIncome;
    }
}