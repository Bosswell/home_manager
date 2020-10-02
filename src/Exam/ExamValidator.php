<?php


namespace App\Exam;

use App\Message\Exam\ValidateExamMessage;


class ExamValidator
{
    private ValidateExamMessage $exam;
    private array $correctQuestions;
    private int $totalPoints = 0;
    private int $correctPoints = 0;
    private int $incorrectPoints = 0;

    public function setExam(ValidateExamMessage $message): self
    {
        $this->exam = $message;

        return $this;
    }

    public function setCorrectQuestions(array $correctQuestions): self
    {
        foreach ($correctQuestions as $question) {
            if (
                !key_exists('questionId', $question) ||
                !key_exists('correctOptions', $question) ||
                !key_exists('optionsNb', $question)
            ) {
                throw new \InvalidArgumentException('Invalid correct questions signature');
            }

            $this->correctQuestions[$question['questionId']] = [
                'correctOptions' => $question['correctOptions'],
                'optionsNb' => $question['optionsNb'],
            ];
        }

        return $this;
    }

    public function validate(): void
    {
        foreach ($this->exam->getQuestionModels() as $question) {
            if ($correctQuestion = $this->correctQuestions[$question->getQuestionId()] ?? null){
                $this->correctPoints += count(
                    array_intersect($question->getCheckedOptions(), $correctQuestion['correctOptions'])
                );
                $this->totalPoints += $correctQuestion['optionsNb'];
            }
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