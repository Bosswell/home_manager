<?php


namespace App\Message\Recipe;


use App\Message\Recipe\Model\ExamListSortBy;

class ListRecipesMessage
{
    private int $nbPage;
    private ?string $searchBy;
    private ExamListSortBy $sortBy;

    public function __construct(?array $data = null)
    {
        $this->nbPage = $data['nbPage'] ?? 1;
        $this->searchBy = $data['searchBy'] ?? null;
        $this->sortBy = new ExamListSortBy($data['sortBy'] ?? null);
    }

    public function getNbPage(): int
    {
        return $this->nbPage;
    }

    public function getSearchBy(): ?string
    {
        return $this->searchBy;
    }

    public function getSortBy(): ExamListSortBy
    {
        return $this->sortBy;
    }
}