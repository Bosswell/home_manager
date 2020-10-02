<?php

namespace App\Entity;

use App\Repository\ExamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isAvailable;

    /**
     * @ORM\Column(type="integer")
     */
    private int $timeout;

    /**
     * @ORM\OneToMany(targetEntity=Question::class, mappedBy="exam")
     */
    private ArrayCollection $questions;

    public function __construct(string $name, string $code, bool $isAvailable, int $timeout, UserInterface $user)
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
        $this->isAvailable = $isAvailable;
        $this->timeout = $timeout;
        $this->questions = new ArrayCollection();
    }

    public function update(string $name, string $code, bool $isAvailable, int $timeout)
    {
        $this->name = $name;
        $this->code = $code;
        $this->isAvailable = $isAvailable;
        $this->timeout = $timeout;
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

    public function getIsAvailable(): ?bool
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
}
