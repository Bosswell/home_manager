<?php

namespace App\Repository;

use App\Entity\Transaction;
use bar\baz\source_with_namespace;
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

    public function findTransactionsTypeSummary(string $transTypeAlias, int $userId, ?DateTime $dateStart = null, ?DateTime $dateEnd = null): QueryBuilder
    {
        $connection = $this->getEntityManager()->getConnection();

        $qb = $connection->createQueryBuilder()
            ->from('transaction', 't')
            ->innerJoin('t', 'transaction_type', $transTypeAlias, $transTypeAlias . '.id = t.transaction_type_id')
            ->where('t.user_id = :userId')
            ->andWhere('t.is_deleted = 0')
            ->setParameter(':userId', $userId)
            ->groupBy($transTypeAlias . '.id');

        if (!is_null($dateStart)) {
            $qb->andWhere('t.created_at >= :dateStart');
            $qb->setParameter(':dateStart', $dateStart->format('Y/m/d'));
        }

        if (!is_null($dateEnd)) {
            // To include full day
            $dateEnd = clone $dateEnd;
            $dateEnd->modify('+1 day');
            $qb->andWhere('t.created_at <= :dateEnd');
            $qb->setParameter(':dateEnd', $dateEnd->format('Y/m/d'));
        }

        return $qb;
    }

    public function findAllTransactionSummary(int $userId, ?DateTime $dateStart = null, ?DateTime $dateEnd = null): array
    {
        $qb = $this->findTransactionsTypeSummary('tts', ...func_get_args());
        $sql = $qb
            ->select('ROUND(SUM(t.amount), 2)')
            ->andWhere('tts.id = tt.id')
            ->andWhere('t.is_income = 1')
            ->getSQL();

        $qb = $this->findTransactionsTypeSummary('tt', ...func_get_args());
        $qb->select('
            tt.id as `transactionTypeId`,
            tt.name, ROUND(SUM(t.amount), 2) as `totalAmount`,
            ('. $sql .') AS `incomeAmount`,
            COUNT(t.id) AS `nbEntries`,
            t.tax_percentage / 100 * t.amount as `deductibleExpanses`
        ');

        return $qb->execute()->fetchAll() ?? [];
    }

    public function getTransactionListQuery(
        int $userId,
        ?int $transTypeId = null,
        ?int $lastDays = null,
        ?int $isIncome = null,
        string $orderBy = 't.id',
        string $orderDirection = 'DESC'
    ): QueryBuilder {
        $connection = $this->getEntityManager()->getConnection();

        $qb = $connection->createQueryBuilder()
            ->select('
                tt.id as `transactionTypeId`,
                t.id,
                t.amount,
                t.created_at,
                t.description,
                tt.name, 
                t.is_income as `isIncome`,
                t.tax_percentage as `taxPercentage`
            ')
            ->from('transaction', 't')
            ->innerJoin('t', 'user', 'u', 'u.id = t.user_id')
            ->innerJoin('t', 'transaction_type', 'tt', 'tt.id = t.transaction_type_id')
            ->where('u.id = :id')
            ->andWhere('t.is_deleted = 0')
            ->setParameter(':id', $userId);

        if (!is_null($transTypeId)) {
            $qb->andWhere('t.transaction_type_id = :transId');
            $qb->setParameter(':transId', $transTypeId);
        }

        if (!is_null($lastDays)) {
            $qb->andWhere('t.created_at >= DATE_ADD(CURDATE(), INTERVAL -:lastDays DAY)');
            $qb->setParameter(':lastDays', $lastDays);
        }

        if (!is_null($isIncome)) {
            $qb->andWhere('t.is_income = :isIncome');
            $qb->setParameter(':isIncome', $isIncome);
        }

        $qb->orderBy($orderBy, $orderDirection);

        return $qb;
    }
}
