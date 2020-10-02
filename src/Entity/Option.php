<?php

namespace App\Entity;

use App\Repository\OptionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=OptionRepository::class)
 */
class Option
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"default"})
     */
    private int $id;

    /**
     * @ORM\Column(type="text")
     * @Groups({"default"})
     */
    private string $content;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isCorrect;

    /**
     * @ORM\ManyToOne(targetEntity=Question::class, inversedBy="answer")
     */
    private Question $question;

    public function __construct(string $content, bool $isCorrect)
    {
        $this->content = $content;
        $this->isCorrect = $isCorrect;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }


    public function getIsCorrect(): ?bool
    {
        return $this->isCorrect;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(Question $question): void
    {
        $this->question = $question;
    }
}
