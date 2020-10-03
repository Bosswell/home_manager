<?php


namespace App\Exam;


class StandardValidator extends AbstractExamValidator
{
    public function validate(): ExamResult
    {
        $totalPoints = count($this->userQuestionsSnippets);
        $correctPoints = 0;

        foreach ($this->userQuestionsSnippets as $question) {
            if (!$correctOptions = $this->correctOptions[$question->getQuestionId()] ?? null) {
                throw new \LogicException(
                    sprintf('Correct question for question with [ id = %d ] does not exist', $question->getQuestionId())
                );
            }

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
}