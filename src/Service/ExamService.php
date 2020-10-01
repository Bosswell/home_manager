<?php

namespace App\Service;

use App\ApiException;
use App\Entity\Exam;
use App\Message\Exam\CreateExamMessage;
use App\Message\Exam\UpdateExamMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;


class ExamService
{
    private EntityManagerInterface $em;
    private ObjectValidator $validator;
    private ?TokenInterface $token;

    public function __construct(EntityManagerInterface $entityManager, ObjectValidator $validator, ContainerInterface $container)
    {
        $this->em = $entityManager;
        $this->validator = $validator;
        /** @var TokenStorageInterface $tokenStorage */
        $tokenStorage = $container->get('security.token_storage');
        $this->token = $tokenStorage ? $tokenStorage->getToken() : null;
    }

    /**
     * @throws ApiException
     */
    public function createExam(CreateExamMessage $message): void
    {
        $exam = new Exam(
            $message->getName(),
            $message->getCode(),
            $this->token->getUser()
        );

        $this->validator->validate($exam);
        $this->em->persist($exam);
        $this->em->flush();
    }

    /**
     * @throws ApiException
     */
    public function updateExam(UpdateExamMessage $message): void
    {
        $user = $this->token->getUser();
        /** @var Exam $recipe */
        $exam = $this->em
            ->getRepository(Exam::class)
            ->findOneBy(['id' => $message->getId(), 'user' => $user]);

        if (is_null($exam)) {
            throw ApiException::entityNotFound(
                $message->getId(),
                get_class($this),
                ['Exam that you try to update does not exists']
            );
        }

        $exam->update($message->getName(), $message->getCode());
        $this->validator->validate($exam);
        $this->em->flush();
    }

    /**
     * @throws ApiException
     */
    public function deleteExam(int $id)
    {
        $user = $this->token->getUser();
        $exam = $this->em
            ->getRepository(Exam::class)
            ->findOneBy(['id' => $id, 'user' => $user]);

        if (is_null($exam)) {
            throw ApiException::entityNotFound(
                $id,
                get_class($this),
                ['Exam that you try to update does not exists']
            );
        }

        $exam->delete();
        $this->em->flush();
    }
}