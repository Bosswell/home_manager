<?php

namespace App\Exam;

use App\ApiException;
use App\Entity\Exam;
use App\Entity\ExamHistory;
use App\Message\Exam\StartExamMessage;
use App\Message\Exam\ValidateExamMessage;
use App\Repository\ExamHistoryRepository;
use App\Repository\ExamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;


class ExamFacade
{
    private ExamRepository $examRepository;
    private EntityManagerInterface $entityManager;
    private ExamHistoryRepository $historyRepository;

    public function __construct(ExamRepository $examRepository, EntityManagerInterface $entityManager, ExamHistoryRepository $historyRepository)
    {
        $this->examRepository = $examRepository;
        $this->entityManager = $entityManager;
        $this->historyRepository = $historyRepository;
    }

    /**
     * @throws ApiException
     */
    public function validateExam(ValidateExamMessage $message): ExamResult
    {
        /** @var ExamHistory $history */
        $history = $this->historyRepository->findOneBy(['userId' => $message->getUserId()]);

        if (is_null($history)) {
            throw new ApiException('You are trying to validate exam which does`t exist', Response::HTTP_BAD_REQUEST);
        }

        if (!$history->isActive()) {
            throw new ApiException('Exam has already be validated', Response::HTTP_BAD_REQUEST);
        }

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

        $history
            ->setResult($result->toArray())
            ->setSnippet($snippets)
            ->deactivate();

        $this->entityManager->flush();

        return $result;
    }

    /**
     * @throws ApiException
     */
    public function startExam(StartExamMessage $message): ExamHistory
    {
        $exam = $this->examRepository->findOneBy([
            'id' => $message->getExamId(),
            'code' => $message->getCode()
        ]);

        if (is_null($exam)) {
            throw ApiException::entityNotFound($message->getExamId(), 'Exam');
        }

        $history = new ExamHistory($exam, $message->getUsername(), $message->getUserNumber());
        $this->entityManager->persist($history);
        $this->entityManager->flush();

        return $history;
    }
}