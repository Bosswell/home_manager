<?php


namespace App\Exam;

use App\Message\Exam\Model\UserQuestionSnippet;


class ExamValidator
{
    /** @var UserQuestionSnippet[] */
    private array $userQuestionsSnippets;

    /** @var QuestionSnippet[] */
    private array $questionsSnippets;

    private int $totalPoints = 0;
    private int $correctPoints = 0;
    private int $incorrectPoints = 0;

    public function setUserQuestionsSnippets(array $userQuestionsSnippets): self
    {
        $this->userQuestionsSnippets = $userQuestionsSnippets;

        return $this;
    }

    public function setQuestionSnippets(array $questionsSnippets): self
    {
        $this->questionsSnippets = $questionsSnippets;

        return $this;
    }

    public function validate(): void
    {
        if (!isset($this->userQuestionsSnippets, $this->questionsSnippets)) {
            throw new \LogicException('Before you make a validation, you need to set questions snippets');
        }

        foreach ($this->userQuestionsSnippets as $question) {
            if (!$snippet = $this->questionsSnippets[$question->getQuestionId()] ?? null) {
                throw new \LogicException(
                    sprintf('Snippet for question with [ id = %d ] does not exist', $question->getQuestionId())
                );
            }

            $this->correctPoints += count(
                array_intersect($question->getCheckedOptions(), $snippet->getCorrectOptions())
            );
            $this->totalPoints += $snippet->getNbOptions();
        }

        $this->incorrectPoints = $this->totalPoints - $this->correctPoints;
    }

    public function getTotalPoints(): int
    {
        return $this->totalPoints;
    }

    public function getCorrectPoints(): int
    {
        return $this->correctPoints;
    }

    public function getInCorrectPoints(): int
    {
        return $this->incorrectPoints;
    }
}