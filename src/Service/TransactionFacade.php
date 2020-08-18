<?php

namespace App\Service;

use App\ApiException;
use App\Entity\Transaction;
use App\Entity\TransactionType;
use App\Message\CreateTransactionMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class TransactionFacade
{
    private EntityManagerInterface $em;
    private ObjectValidator $validator;
    private UserPasswordEncoderInterface $encoder;

    public function __construct(EntityManagerInterface $entityManager, ObjectValidator $validator, UserPasswordEncoderInterface $encoder)
    {
        $this->em = $entityManager;
        $this->validator = $validator;
        $this->encoder = $encoder;
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
                ['Invalid transaction type value']
            );
        }

        $transaction = new Transaction(
            $message->getAmount(),
            $transactionType
        );

        $this->validator->validate($transaction);
    }
}