<?php

namespace App\Entity;

use App\Repository\ExamHistoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=ExamHistoryRepository::class)
 */
class ExamHistory
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="guid")
     */
    private string $id;

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
     * @Assert\Length(
     *     min= 4,
     *     max = 20,
     *     maxMessage = "Your name cannot be longer than {{ limit }} characters",
     *     minMessage = "Your name must contain at least {{ limit }} characters"
     * )
     */
    private string $username;

    /**
     * @ORM\Column(type="integer")
     */
    private int $userNumber;

    /**
     * @ORM\Column(type="guid")
     */
    private string $examUserId;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private ?array $result = [];

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isActive;

    /**
     * @ORM\Column(type="array")
     */
    private array $normalizedExam = [];

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\Choice(
     *     {"standard", "subtraction"},
     *     message="Invalid exam mode."
     *)
     */
    private string $mode;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $startedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private \DateTime $finishedAt;


    public function __construct(Exam $exam, string $examUserId, string $username, string $userNumber, array $normalizedExam, string $mode)
    {
        if (!Uuid::isValid($examUserId)) {
            throw new \InvalidArgumentException('Invalid user id');
        }

        $this->id = Uuid::v4()->toRfc4122();
        $this->examUserId = $examUserId;
        $this->exam = $exam;
        $this->isActive = true;
        $this->username = $username;
        $this->userNumber = $userNumber;
        $this->normalizedExam = $normalizedExam;
        $this->mode = $mode;
        $this->startedAt = new \DateTime();
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function finish(): void
    {
        $this->isActive = false;
        $this->finishedAt = new \DateTime();
    }

    public function getId(): string
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

    public function getExamUserId(): string
    {
        return $this->examUserId;
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

    public function setExam(Exam $exam): void
    {
        $this->exam = $exam;
    }

    public function getNormalizedExam(): array
    {
        return $this->normalizedExam;
    }

    public function getMode(): string
    {
        return $this->mode;
    }

    public function getStartedAt(): \DateTime
    {
        return $this->startedAt;
    }

    public function getFinishedAt(): ?\DateTimeInterface
    {
        return $this->finishedAt;
    }
}
