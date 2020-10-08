<?php


namespace App\Message\Question;


class LinkQuestionMessage
{
    private int $examId = 0;
    private int $questionId = 0;

    public function getExamId(): int
    {
        return $this->examId;
    }

    public function setExamId($examId): void
    {
        $this->examId = (int)$examId;
    }

    public function getQuestionId(): int
    {
        return $this->questionId;
    }

    public function setQuestionId($questionId): void
    {
        $this->questionId = (int)$questionId;
    }
}