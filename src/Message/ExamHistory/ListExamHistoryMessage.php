<?php

namespace App\Message\ExamHistory;

use Symfony\Component\Validator\Constraints as Assert;


class ListExamHistoryMessage
{
    private int $nbPage;
    private ExamHistoryListFilterBy $filterBy;

    /**
     * @Assert\Valid
     */
    private ExamHistoryListSortBy $sortBy;

    public function __construct(?array $data = null)
    {
        $this->nbPage = $data['nbPage'] ?? 1;
        $this->filterBy = new ExamHistoryListFilterBy($data['filterBy'] ?? null);
        $this->sortBy = new ExamHistoryListSortBy($data['sortBy'] ?? null);
    }

    public function getNbPage(): int
    {
        return $this->nbPage;
    }

    public function getFilterBy(): ExamHistoryListFilterBy
    {
        return $this->filterBy;
    }

    public function getSortBy(): ExamHistoryListSortBy
    {
        return $this->sortBy;
    }
}