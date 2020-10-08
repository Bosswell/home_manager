<?php

namespace App\Service;

use App\ApiException;
use App\Entity\Exam;
use App\Entity\Question;
use App\Message\Question\LinkQuestionMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;


class QuestionManager
{
    private EntityManagerInterface $em;
    private ?TokenInterface $token;

    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $storage)
    {
        $this->em = $entityManager;
        $this->token = $storage->getToken();
    }

    /**
     * @throws ApiException
     */
    public function linkQuestionAction(LinkQuestionMessage $message): void
    {
        /** @var Exam $exam */
        [$exam, $question] = $this->getEntities($message);

        $exam->addQuestion($question);
        $this->em->flush();
    }

    /**
     * @throws ApiException
     */
    public function unlinkQuestionAction(LinkQuestionMessage $message): void
    {
        /** @var Exam $exam */
        [$exam, $question] = $this->getEntities($message);

        $exam->removeQuestion($question);
        $this->em->flush();
    }

    /**
     * @throws ApiException
     */
    private function getEntities(LinkQuestionMessage $message): array
    {
        $question = $this->em->getRepository(Question::class)
            ->findOneBy(['id' => $message->getQuestionId(), 'user' => $this->token->getUser()]);

        if (is_null($question)) {
            throw ApiException::entityNotFound($message->getExamId(), Question::class);
        }

        /** @var Exam $exam */
        $exam = $this->em
            ->getRepository(Exam::class)
            ->findOneBy(['id' => $message->getExamId(), 'user' => $this->token->getUser()]);

        if (is_null($question)) {
            throw ApiException::entityNotFound($message->getExamId(), Exam::class);
        }

        return [
            $exam,
            $question
        ];
    }
}