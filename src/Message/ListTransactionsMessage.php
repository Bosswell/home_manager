<?php


namespace App\Message;


use App\Message\Model\TransactionListFilterBy;
use App\Message\Model\TransactionListSortBy;

class ListTransactionsMessage
{
    private int $page;
    private TransactionListFilterBy $filterBy;
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

    public function setPage($page): void
    {
        $this->page = (int)$page;
    }

    public function getFilterBy(): TransactionListFilterBy
    {
        return $this->filterBy;
    }
}