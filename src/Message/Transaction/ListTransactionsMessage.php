<?php

namespace App\Message\Transaction;

use App\Message\Transaction\Model\TransactionListFilterBy;
use App\Message\Transaction\Model\TransactionListSortBy;
use Symfony\Component\Validator\Constraints as Assert;


class ListTransactionsMessage
{
    private int $nbPage;
    private TransactionListFilterBy $filterBy;

    /**
     * @Assert\Valid
     */
    private TransactionListSortBy $sortBy;

    public function __construct(?array $data = null)
    {
        $this->nbPage = $data['nbPage'] ?? 1;
        $this->filterBy = new TransactionListFilterBy($data['filterBy'] ?? null);
        $this->sortBy = new TransactionListSortBy($data['sortBy'] ?? null);
    }

    public function getNbPage(): int
    {
        return $this->nbPage;
    }

    public function getFilterBy(): TransactionListFilterBy
    {
        return $this->filterBy;
    }

    public function getSortBy(): TransactionListSortBy
    {
        return $this->sortBy;
    }
}