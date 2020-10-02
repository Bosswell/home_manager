<?php

namespace App\Message\Exam;

use App\Message\Exam\Model\UserQuestionSnippet;


class ValidateExamMessage
{
    private ?string $userId;
    private int $examId;

    /** @var UserQuestionSnippet[] */
    private ?array $snippets = null;

    public function __construct(?array $data = null)
    {
        $this->examId = $data['examId'] ?? 0;
        $this->userId = $data['userId'] ?? null;

        foreach ($data['snippets'] ?? [] as $snippet) {
            $this->snippets[] = new UserQuestionSnippet($snippet);
        }
    }

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

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId($userId): void
    {
        $this->userId = (string)$userId;
    }

    public function setExamId($examId): void
    {
        $this->examId = (int)$examId;
    }

    /**
     * @param UserQuestionSnippet[] $snippets
     */
    public function setSnippets(array $snippets): void
    {
        foreach ($snippets as $snippet) {
            $this->snippets[] = new UserQuestionSnippet($snippet);
        }
    }
}