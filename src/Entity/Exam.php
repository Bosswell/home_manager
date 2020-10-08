<?php

namespace App\Entity;

use App\Repository\ExamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=ExamRepository::class)
 */
class Exam
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"default", "details"})
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"default", "details"})
     * @Assert\Length(
     *     min= 1,
     *     max = 255,
     *     maxMessage = "Exam name cannot be longer than {{ limit }} characters",
     *     minMessage = "Exam name cannot be empty"
     * )
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=20)
     * @Groups({"details"})
     * @Assert\Length(
     *     min= 1,
     *     max = 20,
     *     maxMessage = "Exam code cannot be longer than {{ limit }} characters",
     *     minMessage = "Exam code cannot be empty"
     * )
     */
    private string $code;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTime $updatedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isDeleted;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="exams")
     * @ORM\JoinColumn(nullable=false)
     */
    private UserInterface $user;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"details"})
     */
    private bool $isAvailable;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"default", "details"})
     * @Assert\GreaterThan(
     *     value=0,
     *     message="Timeout need to be grater then 0"
     * )
     */
    private int $timeout;

    /**
     * @ORM\OneToMany(targetEntity=ExamHistory::class, mappedBy="exam")
     */
    private Collection $examHistories;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"details"})
     */
    private bool $hasVisibleResult;

    /**
     * @ORM\Column(type="string", length=20)
     * @Groups({"details"})
     * @Assert\Choice(
     *     {"standard", "subtraction"},
     *     message="Invalid exam mode."
     *)
     */
    private string $mode;

    /**
     * @ORM\ManyToMany(targetEntity=Question::class, inversedBy="exams")
     * @Groups({"details"})
     */
    private Collection $questions;

    public function __construct(
        string $name,
        string $code,
        bool $isAvailable,
        int $timeout,
        bool $hasVisibleResult,
        string $mode,
        UserInterface $user
    ) {
        $this->name = $name;
        $this->code = $code;
        $this->createdAt = new \DateTime();
        $this->isDeleted = false;
        $this->user = $user;
        $this->isAvailable = $isAvailable;
        $this->timeout = $timeout;
        $this->questions = new ArrayCollection();
        $this->examHistories = new ArrayCollection();
        $this->hasVisibleResult = $hasVisibleResult;
        $this->mode = $mode;
    }

    public function update(
        string $name,
        string $code,
        bool $isAvailable,
        int $timeout,
        bool $hasVisibleResult,
        string $mode
    ): void {
        $this->name = $name;
        $this->code = $code;
        $this->isAvailable = $isAvailable;
        $this->timeout = $timeout;
        $this->updatedAt = new \DateTime();
        $this->hasVisibleResult = $hasVisibleResult;
        $this->mode = $mode;
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

    public function isAvailable(): ?bool
    {
        return $this->isAvailable;
    }

    public function getTimeout(): ?int
    {
        return $this->timeout;
    }

    /**
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setExam($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->contains($question)) {
            $this->questions->removeElement($question);
            // set the owning side to null (unless already changed)
            if ($question->getExam() === $this) {
                $question->setExam(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ExamHistory[]
     */
    public function getExamHistories(): Collection
    {
        return $this->examHistories;
    }

    public function addExamHistory(ExamHistory $examHistory): self
    {
        if (!$this->examHistories->contains($examHistory)) {
            $this->examHistories[] = $examHistory;
            $examHistory->setExam($this);
        }

        return $this;
    }

    public function removeExamHistory(ExamHistory $examHistory): self
    {
        if ($this->examHistories->contains($examHistory)) {
            $this->examHistories->removeElement($examHistory);
            // set the owning side to null (unless already changed)
            if ($examHistory->getExam() === $this) {
                $examHistory->setExam(null);
            }
        }

        return $this;
    }

    public function setUser(UserInterface $user): void
    {
        $this->user = $user;
    }

    public function hasVisibleResult(): ?bool
    {
        return $this->hasVisibleResult;
    }

    public function getMode(): ?string
    {
        return $this->mode;
    }

    public function setMode(string $mode): self
    {
        $this->mode = $mode;

        return $this;
    }
}
