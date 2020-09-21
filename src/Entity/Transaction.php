<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TransactionRepository::class)
 */
class Transaction implements JsonSerializable
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
    private DateTime $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private DateTime $updatedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private string $description;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="transactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private UserInterface $user;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isDeleted;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isIncome;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $taxPercentage;


    public function __construct(
        bool $isIncome,
        float $amount,
        string $description,
        ?int $taxPercentage,
        TransactionType $transactionType,
        UserInterface $user
    ) {
        $this->isIncome = $isIncome;
        $this->amount = $amount;
        $this->transactionType = $transactionType;
        $this->description = $description;
        $this->createdAt = new \DateTime();
        $this->isDeleted = false;
        $this->user = $user;
        $this->taxPercentage = $taxPercentage;
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

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function jsonSerialize() 
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'description' => $this->description
        ];
    }

    public function isDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    public function update(bool $isIncome, float $amount, string $description, ?int $taxPercentage, TransactionType $transactionType)
    {
        $this->updatedAt = new DateTime();
        $this->amount = $amount;
        $this->isIncome = $isIncome;
        $this->description = $description;
        $this->transactionType = $transactionType;
        $this->taxPercentage = $taxPercentage;
    }

    public function getIsIncome(): ?bool
    {
        return $this->isIncome;
    }

    public function setIsIncome(bool $isIncome): self
    {
        $this->isIncome = $isIncome;

        return $this;
    }

    public function getTaxPercentage(): ?int
    {
        return $this->taxPercentage;
    }
}
