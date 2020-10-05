<?php

namespace App\Message\Transaction;


class UpdateTransactionMessage extends CreateTransactionMessage
{
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = (int)$id;
    }
}