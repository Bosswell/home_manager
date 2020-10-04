<?php

namespace App\Message\Recipe;


class CreateRecipeMessage
{
    protected string $name;
    protected string $content;

    public function __construct(?array $data = null)
    {
        $this->name = $data['name'] ?? '';
        $this->content = $data['content'] ?? '';
    }

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