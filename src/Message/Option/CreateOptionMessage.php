<?php


namespace App\Message\Option;


class CreateOptionMessage
{
    protected string $content = '';
    protected bool $isCorrect = false;
    private int $questionId = 0;

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent($content): void
    {
        $this->content = (string)$content;
    }

    public function getQuestionId(): int
    {
        return $this->questionId;
    }

    public function setQuestionId($questionId): void
    {
        $this->questionId = (int)$questionId;
    }

    public function isCorrect(): ?bool
    {
        return $this->isCorrect;
    }

    public function setIsCorrect($isCorrect): void
    {
        $this->isCorrect = (bool)$isCorrect;
    }
}