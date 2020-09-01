<?php


namespace App\Message;

use App\Message\Model\TransactionListFilterBy;
use App\Message\Model\TransactionListSortBy;
use Symfony\Component\Validator\Constraints as Assert;


class ListTransactionsMessage
{
    private int $page;
    private TransactionListFilterBy $filterBy;

    /** @Assert\Valid */
    private TransactionListSortBy $sortBy;

    public function __construct()
    {
        $this->page = 1;
        $this->filterBy = new TransactionListFilterBy();
        $this->sortBy = new TransactionListSortBy();
    }

    public function getPage(): int
    {
        return $this->page;
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