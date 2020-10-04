<?php

namespace App\Message\Transaction;


class UpdateTransactionMessage extends CreateTransactionMessage
{
    private ?int $id;

    public function __construct(?array $data = null)
    {
        parent::__construct($data);

        $this->id = $data['id'] ?? null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = (int)$id;
    }
}