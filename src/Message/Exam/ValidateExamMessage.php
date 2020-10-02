<?php

namespace App\Message\Exam;

use App\Message\Exam\Model\ValidateQuestionModel;


class ValidateExamMessage
{
    /** @var ValidateQuestionModel[] */
    private ?array $questionModels = null;

    public function __construct(?array $data = null)
    {
        foreach ($data['questions'] ?? [] as $question) {
            $this->questionModels[] = new ValidateQuestionModel($question);
        }
    }

    /**
     * @return ValidateQuestionModel[]
     */
    public function getQuestionModels(): array
    {
        return $this->questionModels;
    }
}