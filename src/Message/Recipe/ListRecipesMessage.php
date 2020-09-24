<?php


namespace App\Message\Recipe;


class ListRecipesMessage
{
    private int $nbPage;

    public function __construct(?array $data = null)
    {
        $this->nbPage = $data['nbPage'] ?? 0;
    }

    public function getNbPage(): int
    {
        return $this->nbPage;
    }

    public function setNbPage($nbPage): void
    {
        $this->nbPage = (int)$nbPage;
    }
}