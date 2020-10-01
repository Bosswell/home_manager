<?php

namespace App\Entity;

use App\Repository\ExamRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=ExamRepository::class)
 */
class Exam
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private string $code;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private \DateTime $updatedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isDeleted;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="exams")
     * @ORM\JoinColumn(nullable=false)
     */
    private UserInterface $user;

    public function __construct(string $name, string $code, UserInterface $user)
    {
        $this->name = $name;
        $this->code = $code;
        $this->createdAt = new \DateTime();
        $this->isDeleted = false;
        $this->user = $user;
    }

    public function update(string $name, string $code)
    {
        $this->name = $name;
        $this->code = $code;
        $this->updatedAt = new \DateTime();
    }

    public function delete()
    {
        $this->isDeleted = true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function isDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }
}
