<?php

namespace App\Repository;

use App\Entity\Exam;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Exam|null find($id, $lockMode = null, $lockVersion = null)
 * @method Exam|null findOneBy(array $criteria, array $orderBy = null)
 * @method Exam[]    findAll()
 * @method Exam[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Exam::class);
    }

    public function getExamsListQuery(int $userId, string $orderBy = 'e.id', string $orderDirection = 'DESC'): QueryBuilder
    {
        $connection = $this->getEntityManager()->getConnection();

        $qb = $connection->createQueryBuilder()
            ->select('e.id, e.name, e.code, e.created_at')
            ->from('exam', 'e')
            ->innerJoin('e', 'user', 'u', 'u.id = e.user_id')
            ->where('u.id = :id')
            ->andWhere('e.is_deleted = 0')
            ->setParameter(':id', $userId);

        $qb->orderBy($orderBy, $orderDirection);

        return $qb;
    }
}
