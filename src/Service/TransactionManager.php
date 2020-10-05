<?php

namespace App\Service;

use App\ApiException;
use App\Entity\Transaction;
use App\Entity\TransactionType;
use App\Message\Transaction\CreateTransactionMessage;
use App\Message\Transaction\UpdateTransactionMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;


class TransactionManager
{
    private EntityManagerInterface $em;
    private ObjectValidator $validator;
    private ?TokenInterface $token;

    public function __construct(EntityManagerInterface $entityManager, ObjectValidator $validator, TokenStorageInterface $storage)
    {
        $this->em = $entityManager;
        $this->validator = $validator;
        $this->token = $storage->getToken();
    }

    /**
     * @param CreateTransactionMessage $message
     * @throws ApiException
     */
    public function createTransaction(CreateTransactionMessage $message): void
    {
        /** @var TransactionType|null $transactionType */
        $transactionType = $this->em
            ->getRepository(TransactionType::class)
            ->find($message->getTransactionTypeId());

        if (is_null($transactionType)) {
            throw ApiException::entityNotFound(
                $message->getTransactionTypeId(),
                get_class($this),
                ['Invalid transaction type value']
            );
        }
        
        $transaction = new Transaction(
            $message->isIncome(),
            $message->getAmount(),
            $message->getDescription(),
            $message->getTaxPercentage(),
            $transactionType,
            $this->token->getUser()
        );

        $this->validator->validate($transaction);
        $this->em->persist($transaction);
        $this->em->flush();
    }

    /**
     * @throws ApiException
     */
    public function deleteTransaction(int $id)
    {
        $user = $this->token->getUser();
        $transaction = $this->em
            ->getRepository(Transaction::class)
            ->findOneBy(['id' => $id, 'user' => $user]);

        if (is_null($transaction)) {
            throw ApiException::entityNotFound(
                $id,
                get_class($this),
                ['Transaction that you try to remove does not exists']
            );
        }

        $transaction->delete();
        $this->em->flush();
    }

    /**
     * @throws ApiException
     */
    public function updateTransaction(UpdateTransactionMessage $message)
    {
        $user = $this->token->getUser();
        /** @var Transaction $transaction */
        $transaction = $this->em
            ->getRepository(Transaction::class)
            ->findOneBy(['id' => $message->getId(), 'user' => $user]);

        if (is_null($transaction)) {
            throw ApiException::entityNotFound(
                $message->getId(),
                get_class($this),
                ['Transaction that you try to update does not exists']
            );
        }

        /** @var TransactionType $transactionType */
        $transactionType = $this->em
            ->getRepository(TransactionType::class)
            ->find($message->getTransactionTypeId());

        if (is_null($transactionType)) {
            throw ApiException::entityNotFound(
                $message->getTransactionTypeId(),
                get_class($this),
                ['Invalid transaction type value']
            );
        }

        $transaction->update(
            $message->isIncome(),
            $message->getAmount(),
            $message->getDescription(),
            $message->getTaxPercentage(),
            $transactionType
        );
        $this->validator->validate($transaction);
        $this->em->flush();
    }
}