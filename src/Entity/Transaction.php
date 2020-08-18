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
     * @Assert\GreaterThan(
     *     value=0,
     *     message="The amount should be greater then {{ compared_value }}"
     * )
     */
    private float $amount;

    /**
     * @ORM\ManyToOne(targetEntity=TransactionType::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private TransactionType $transactionType;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $createdAt;

    public function __construct(float $amount, TransactionType $transactionType)
    {
        $this->amount = $amount;
        $this->transactionType = $transactionType;
        $this->createdAt = new \DateTime();
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
