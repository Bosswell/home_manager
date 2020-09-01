<?php


namespace App\Message\Model;


class TransactionListFilterBy
{
    private ?int $transactionTypeId;

    public function __construct()
    {
        $this->transactionTypeId = null;
    }

    public function getTransactionTypeId(): ?int
    {
        return $this->transactionTypeId;
    }
}