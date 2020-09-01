<?php


namespace App\Message\Model;


class TransactionListFilterBy
{
    private ?int $transactionTypeId = null;
    private ?int $lastDays = null;

    public function getTransactionTypeId(): ?int
    {
        return $this->transactionTypeId;
    }

    public function getLastDays(): ?int
    {
        return $this->lastDays;
    }
}
