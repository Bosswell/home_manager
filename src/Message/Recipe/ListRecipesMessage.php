<?php

namespace App\Message\Recipe;

use App\Message\Recipe\Model\RecipeListSortBy;
use Symfony\Component\Validator\Constraints as Assert;


class ListRecipesMessage
{
    private int $nbPage;
    private ?string $searchBy;

    /** @Assert\Valid */
    private RecipeListSortBy $sortBy;

    public function __construct(?array $data = null)
    {
        $this->nbPage = $data['nbPage'] ?? 1;
        $this->searchBy = $data['searchBy'] ?? null;
        $this->sortBy = new RecipeListSortBy($data['sortBy'] ?? null);
    }

    public function getNbPage(): int
    {
        return $this->nbPage;
    }

    public function getSearchBy(): ?string
    {
        return $this->searchBy;
    }

    public function getSortBy(): RecipeListSortBy
    {
        return $this->sortBy;
    }
}