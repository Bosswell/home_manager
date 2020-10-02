<?php

namespace App\Message\Exam;

use App\Message\Exam\Model\UserQuestionSnippet;


class ValidateExamMessage
{
    /** @var UserQuestionSnippet[] */
    private ?array $snippets = null;

    public function __construct(?array $data = null)
    {
        foreach ($data['questions'] ?? [] as $question) {
            $this->snippets[] = new UserQuestionSnippet($question);
        }
    }

    /**
     * @return UserQuestionSnippet[]
     */
    public function getUserQuestionsSnippets(): array
    {
        return $this->snippets;
    }
}