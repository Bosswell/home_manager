<?php

namespace App\Factory;

use Doctrine\DBAL\Query\QueryBuilder;
use Pagerfanta\Doctrine\DBAL\QueryAdapter;
use Pagerfanta\Pagerfanta;


class PagerfantaFactory
{
    public static function build(QueryBuilder $builder): Pagerfanta
    {
        $countQueryBuilderModifier = function (QueryBuilder $queryBuilder): void {
            $queryBuilder->select('COUNT(DISTINCT t.id) AS total_results')
                ->setMaxResults(1);
        };

        $adapter = new QueryAdapter($builder, $countQueryBuilderModifier);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(10);

        return $pagerfanta;
    }
}