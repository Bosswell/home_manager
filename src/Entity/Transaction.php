<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TransactionRepository::class)
 */
class Transaction
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="float")
     * @Assert\GreaterThan(0)
     */
    private float $amount;

    /**
     * @ORM\ManyToOne(targetEntity=TransactionType::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private TransactionType $transactionType;

    public function __construct(float $amount, TransactionType $transactionType)
    {
        $this->amount = $amount;
        $this->transactionType = $transactionType;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getTransactionType(): ?TransactionType
    {
        return $this->transactionType;
    }
}
