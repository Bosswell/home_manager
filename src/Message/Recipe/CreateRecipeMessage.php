<?php

namespace App\Message\Recipe;


class CreateRecipeMessage
{
    private string $name;
    private string $content;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = (string)$name;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent($content): void
    {
        $this->content = (string)$content;
    }
}