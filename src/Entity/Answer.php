<?php

namespace App\Entity;

use App\Repository\AnswerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnswerRepository::class)
 */
class Answer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="text")
     */
    private string $text;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isCorrect;

    /**
     * @ORM\ManyToOne(targetEntity=Question::class, inversedBy="answer")
     */
    private Question $question;

    public function __construct(string $text, bool $isCorrect)
    {
        $this->text = $text;
        $this->isCorrect = $isCorrect;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }


    public function getIsCorrect(): ?bool
    {
        return $this->isCorrect;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }
}
