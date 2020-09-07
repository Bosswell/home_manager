<?php

namespace App\Repository;

use App\Entity\Transaction;
use DateTime;
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

    public function findAllTransactionSummary(int $userId, ?DateTime $dateStart = null, ?DateTime $dateEnd = null): array
    {
        $connection = $this->getEntityManager()->getConnection();

        $qb = $connection->createQueryBuilder()
            ->select('tt.id as `transactionTypeId`, tt.name, ROUND(SUM(t.amount), 2) AS `amount`, COUNT(t.id) AS `entries`')
            ->from('transaction', 't')
            ->innerJoin('t', 'transaction_type', 'tt', 'tt.id = t.transaction_type_id')
            ->where('t.user_id = :userId')
            ->setParameter(':userId', $userId)
            ->groupBy('tt.id');

        if (!is_null($dateStart)) {
            $qb->andWhere('t.created_at >= :dateStart');
            $qb->setParameter(':dateStart', $dateStart->format('Y/m/d'));
        }

        if (!is_null($dateEnd)) {
            $qb->andWhere('t.created_at < :dateEnd');
            $qb->setParameter(':dateEnd', $dateEnd->format('Y/m/d'));
        }

        return $qb->execute()->fetchAll() ?? [];
    }

    public function getTransactionListQuery(
        int $userId,
        ?int $transTypeId = null,
        ?int $lastDays = null,
        string $orderBy = 't.id',
        string $orderDirection = 'DESC'
    ): QueryBuilder {
        $connection = $this->getEntityManager()->getConnection();

        $qb = $connection->createQueryBuilder()
            ->select('t.amount, t.created_at, t.description, tt.name')
            ->from('transaction', 't')
            ->innerJoin('t', 'user', 'u', 'u.id = t.user_id')
            ->innerJoin('t', 'transaction_type', 'tt', 'tt.id = t.transaction_type_id')
            ->where('u.id = :id')
            ->setParameter(':id', $userId);

        if (!is_null($transTypeId)) {
            $qb->andWhere('t.transaction_type_id = :transId');
            $qb->setParameter(':transId', $transTypeId);
        }

        if (!is_null($lastDays)) {
            $qb->andWhere('t.created_at >= DATE_ADD(CURDATE(), INTERVAL -:lastDays DAY)');
            $qb->setParameter(':lastDays', $lastDays);
        }

        $qb->orderBy($orderBy, $orderDirection);

        return $qb;
    }
}
