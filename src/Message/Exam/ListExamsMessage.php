<?php

namespace App\Message\Exam;

use App\Message\Exam\Model\ExamListSortBy;
use Symfony\Component\Validator\Constraints as Assert;


class ListExamsMessage
{
    private int $nbPage;

    /** @Assert\Valid */
    private ExamListSortBy $sortBy;

    public function __construct(?array $data = null)
    {
        $this->nbPage = $data['nbPage'] ?? 1;
        $this->sortBy = new ExamListSortBy($data['sortBy'] ?? null);
    }

    public function getNbPage(): int
    {
        return $this->nbPage;
    }

    public function getSortBy(): ExamListSortBy
    {
        return $this->sortBy;
    }
}
