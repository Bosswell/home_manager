<?php


namespace App\Exam;

use App\Message\Exam\Model\UserQuestionSnippet;


class ExamValidator
{
    /** @var UserQuestionSnippet[] */
    private array $userQuestionsSnippets;

    /** @var QuestionSnippet[] */
    private array $questionsSnippets;
    private ExamResult $examResult;

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

    public function getExamResult(): ExamResult
    {
        return $this->examResult;
    }

    public function validate(): void
    {
        if (!isset($this->userQuestionsSnippets, $this->questionsSnippets)) {
            throw new \LogicException('Before you make a validation, you need to set questions snippets');
        }

        $correctPoints = $totalPoints = 0;
        foreach ($this->userQuestionsSnippets as $question) {
            if (!$snippet = $this->questionsSnippets[$question->getQuestionId()] ?? null) {
                throw new \LogicException(
                    sprintf('Snippet for question with [ id = %d ] does not exist', $question->getQuestionId())
                );
            }

            $correctPoints += count(
                array_intersect($question->getCheckedOptions(), $snippet->getCorrectOptions())
            );
            $totalPoints += $snippet->getNbOptions();
        }

        $this->examResult = new ExamResult($correctPoints, $totalPoints);
    }
}