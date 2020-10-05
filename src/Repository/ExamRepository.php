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

    /**
     * Output:
     * [
     *     [
     *         'questionId' => 1,
     *         'correctOptions' => '1, 2',
     *         'nbOptions' => 5
     *     ],
     *     ....
     * ]
     */
    public function getCorrectOptions(int $examId): array
    {
        $connection = $this->getEntityManager()->getConnection();

        $subQuery = $connection->createQueryBuilder()
            ->select('GROUP_CONCAT(oo.id SEPARATOR ",")')
            ->from('option', 'oo')
            ->where('oo.is_correct = 1')
            ->andWhere('oo.question_id = q.id');

        $qb = $connection->createQueryBuilder()
            ->select('q.id, ('. $subQuery->getSQL() .') as `correctOptions`')
            ->from('exam', 'e')
            ->innerJoin('e', 'question', 'q', 'e.id = q.exam_id')
            ->innerJoin('q', 'option', 'o', 'o.question_id = q.id')
            ->where('e.id = :id')
            ->andWhere('e.is_deleted = 0')
            ->setParameter(':id', $examId)
            ->groupBy('q.id')
        ;

        return $qb
            ->execute()
            ->fetchAll();
    }
}
