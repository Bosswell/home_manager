<?php


namespace App\Exam;


class StandardValidator extends AbstractExamValidator
{
    public function validate(): ExamResult
    {
        $totalPoints = count($this->userQuestionsSnippets);
        $correctPoints = 0;

        foreach ($this->userQuestionsSnippets as $question) {
            $correctOptions = $this->correctOptions[$question->getQuestionId()] ?? [];

            $correctAnswers = count(array_intersect($question->getCheckedOptions(), $correctOptions));
            $totalCorrectAnswers = count($correctOptions);
            $userCheckedAnswers = count($question->getCheckedOptions());

            if (
                $totalCorrectAnswers === $correctAnswers &&
                $totalCorrectAnswers === $userCheckedAnswers
            ) {
                $correctPoints += 1;
            }
        }

        return new ExamResult(
            $correctPoints,
            $totalPoints,
            $totalPoints - $correctPoints,
            $correctPoints ? $correctPoints / $totalPoints * 100 : 0
        );
    }

    public static function getMode(): string
    {
        return self::STANDARD_MODE;
    }
}