<?php

namespace App\Message\Exam;

use App\Message\Exam\Model\UserQuestionSnippet;


class ValidateExamMessage
{
    private int $examId;
    private string $historyId;

    /** @var UserQuestionSnippet[] */
    private ?array $snippets = null;

    public function getExamId(): int
    {
        return $this->examId;
    }

    /**
     * @return UserQuestionSnippet[]
     */
    public function getUserQuestionsSnippets(): array
    {
        return $this->snippets;
    }

    public function setExamId($examId): void
    {
        $this->examId = (int)$examId;
    }

    public function setSnippets(array $snippets): void
    {
        foreach ($snippets as $snippet) {
            $this->snippets[] = new UserQuestionSnippet($snippet);
        }
    }

    public function getHistoryId(): string
    {
        return $this->historyId;
    }

    public function setHistoryId($historyId): void
    {
        $this->historyId = $historyId;
    }
}