<?php

namespace App\Exam;


class SubtractionExamValidator extends AbstractExamValidator
{
    public function validate(): ExamResult
    {
        $totalCorrect = $totalCorrectOptions = $totalIncorrectAnswers = 0;

        foreach ($this->userQuestionsSnippets as $question) {
            $correctOptions = $this->correctOptions[$question->getQuestionId()] ?? [];

            $totalCorrect += count(array_intersect($question->getCheckedOptions(), $correctOptions));
            $totalCorrectOptions += count($correctOptions);
            $totalIncorrectAnswers += count(array_diff($question->getCheckedOptions(), $correctOptions));
        }

        return new ExamResult(
            $totalCorrect,
            $totalCorrectOptions,
            $totalIncorrectAnswers,
            $this->getPercentage($totalCorrect, $totalCorrectOptions, $totalIncorrectAnswers)
        );
    }

    private function getPercentage(int $totalCorrect, int $totalCorrectOptions, int $totalIncorrectAnswers): float
    {
        if ($totalIncorrectAnswers === 0) {
            return 0;
        }

        $numeral = $totalCorrect - $totalIncorrectAnswers;

        if ($numeral < 0) {
            $numeral = 0;
        }

        return $numeral / $totalCorrectOptions * 100;
    }
}