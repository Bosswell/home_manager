<?php

namespace App\Factory;

use Doctrine\DBAL\Query\QueryBuilder;
use Pagerfanta\Doctrine\DBAL\QueryAdapter;
use Pagerfanta\Pagerfanta;


class PagerfantaFactory
{
    public static function build(QueryBuilder $builder, string $alias): Pagerfanta
    {
        $countQueryBuilderModifier = function (QueryBuilder $queryBuilder) use ($alias) : void {
            $queryBuilder->select(sprintf('COUNT(DISTINCT %s.id) AS total_results', $alias))
                ->setMaxResults(1);
        };

        $adapter = new QueryAdapter($builder, $countQueryBuilderModifier);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(10);

        return $pagerfanta;
    }
}