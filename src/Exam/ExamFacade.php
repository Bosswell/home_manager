<?php

namespace App\Exam;

use App\Message\Exam\ValidateExamMessage;
use App\Repository\ExamRepository;


class ExamFacade
{
    private ExamRepository $examRepository;

    public function __construct(ExamRepository $examRepository)
    {
        $this->examRepository = $examRepository;
    }

    public function validateExam(ValidateExamMessage $message): ExamResult
    {
        $normalizer = new QuestionSnippetNormalizer();
        $snippets = $normalizer->normalizeArray(
            $this->examRepository->getQuestionsSnippets($message->getExamId())
        );

        $examValidator = new ExamValidator();
        $examValidator->setQuestionSnippets($snippets);
        $examValidator->setUserQuestionsSnippets($message->getUserQuestionsSnippets());

        $examValidator->validate();

        $result = $examValidator->getExamResult();
        $result->setQuestionsSnippets($snippets);

        return $result;
    }
}