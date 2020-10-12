<?php

namespace App\Repository;

use App\Entity\ExamHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ExamHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExamHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExamHistory[]    findAll()
 * @method ExamHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExamHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExamHistory::class);
    }

    public function getExamHistory(
        int $userId,
        ?string $username = null,
        ?int $userNumber = null,
        ?bool $isActive = null,
        ?\DateTime $dateStart = null,
        ?\DateTime $dateEnd = null,
        string $orderBy = 'eh.id',
        string $orderDirection = 'DESC'
    ): QueryBuilder {
        $connection = $this->getEntityManager()->getConnection();

        $qb = $connection->createQueryBuilder()
            ->select('eh.*. e.name')
            ->from('exam_history', 'eh')
            ->innerJoin('e', 'exam', 'e', 'e.id = eh.exam_id')
            ->where('e.user_id = :userId')
            ->setParameter(':userId', $userId)
        ;

        if (!is_null($username)) {
            $qb->andWhere('th.username LIKE :username');
            $qb->setParameter(':username', '%' . $username . '%');
        }

        if (!is_null($userNumber)) {
            $qb->andWhere('th.user_number LIKE :userNumber');
            $qb->setParameter(':userNumber', '%' . $userNumber . '%');
        }

        if (!is_null($isActive)) {
            $qb->andWhere('th.is_active = :isActive');
            $qb->setParameter(':isActive', $isActive);
        }

        if (!is_null($dateStart)) {
            $qb->andWhere('t.started_at >= :dateStart');
            $qb->setParameter(':dateStart', $dateStart->format('Y/m/d'));
        }

        if (!is_null($dateEnd)) {
            // To include full day
            $dateEnd = clone $dateEnd;
            $dateEnd->modify('+1 day');
            $qb->andWhere('t.finished_at <= :dateEnd');
            $qb->setParameter(':dateEnd', $dateEnd->format('Y/m/d'));
        }

        $qb->orderBy($orderBy, $orderDirection);

        return $qb;
    }
}
