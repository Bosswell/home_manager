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

        foreach ($data['questions'] ?? [] as $question) {
            $this->snippets[] = new UserQuestionSnippet($question);
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
}