<?php


namespace App\Message\Question;


class CreateQuestionMessage
{
    protected string $query = '';
    private int $examId = 0;

    public function getQuery(): string
    {
        return $this->query;
    }

    public function setQuery($query): void
    {
        $this->query = (string)$query;
    }

    public function getExamId(): int
    {
        return $this->examId;
    }

    public function setExamId($examId): void
    {
        $this->examId = (int)$examId;
    }
}