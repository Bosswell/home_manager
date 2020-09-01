<?php

namespace App\Repository;

use App\Entity\Transaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Transaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transaction[]    findAll()
 * @method Transaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    public function findAllTransactionSummary(): array
    {
        $connection = $this->getEntityManager()->getConnection();

        $qb = $connection->createQueryBuilder()
            ->select('tt.id as `transactionTypeId`, tt.name, SUM(t.amount) AS `amount`, COUNT(t.id) AS `entries`')
            ->from('transaction', 't')
            ->innerJoin('t', 'transaction_type', 'tt', 'tt.id = t.transaction_type_id')
            ->groupBy('tt.id');

        return $qb->execute()->fetchAll() ?? [];
    }

    public function getTransactionListQuery(int $userId, ?int $transTypeId = null): QueryBuilder
    {
        $connection = $this->getEntityManager()->getConnection();

        $qb = $connection->createQueryBuilder()
            ->select('t.amount, t.created_at, t.description, tt.name')
            ->from('transaction', 't')
            ->innerJoin('t', 'user', 'u', 'u.id = t.user_id')
            ->innerJoin('t', 'transaction_type', 'tt', 'tt.id = t.transaction_type_id')
            ->where('u.id = :id')
            ->setParameter(':id', $userId);

        if ($transTypeId) {
            $qb->andWhere('t.transaction_type_id = :transId');
            $qb->setParameter(':transId', $transTypeId);
        }

        return $qb;
    }
}
