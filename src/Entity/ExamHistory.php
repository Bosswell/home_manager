<?php

namespace App\Entity;

use App\Repository\ExamHistoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=ExamHistoryRepository::class)
 */
class ExamHistory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity=Exam::class, inversedBy="examHistories")
     * @ORM\JoinColumn(nullable=false)
     */
    private Exam $exam;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private ?array $snippet = [];

    /**
     * @ORM\Column(type="string", length=20)
     */
    private string $username;

    /**
     * @ORM\Column(type="integer")
     */
    private int $userNumber;

    /**
     * @ORM\Column(type="guid")
     */
    private string $userId;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private ?array $result = [];

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isActive;

    public function __construct(Exam $exam, string $username, string $userNumber)
    {
        $this->userId = Uuid::v4()->toRfc4122();
        $this->exam = $exam;
        $this->isActive = true;
        $this->username = $username;
        $this->userNumber = $userNumber;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function deactivate(): void
    {
        $this->isActive = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExam(): Exam
    {
        return $this->exam;
    }

    public function getSnippet(): ?array
    {
        return $this->snippet;
    }

    public function setSnippet(?array $snippet): self
    {
        $this->snippet = $snippet;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getUserNumber(): ?int
    {
        return $this->userNumber;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getResult(): ?array
    {
        return $this->result;
    }

    public function setResult(?array $result): self
    {
        $this->result = $result;

        return $this;
    }
}
