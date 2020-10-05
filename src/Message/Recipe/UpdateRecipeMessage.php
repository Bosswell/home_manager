<?php

namespace App\Message\Recipe;


class UpdateRecipeMessage extends CreateRecipeMessage
{
    private int $id = 0;

    public function setId($id): void
    {
        $this->id = (int)$id;
    }

    public function getId()
    {
        return $this->id;
    }
}