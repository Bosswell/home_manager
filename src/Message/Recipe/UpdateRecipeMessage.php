<?php

namespace App\Message\Recipe;


class UpdateRecipeMessage
{
    private int $id;
    private string $name;
    private string $content;

    public function __construct(?array $data = null)
    {
        $this->id = $data['id'] ?? 0;
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

    public function setId($id): void
    {
        $this->id = (int)$id;
    }

    public function getId()
    {
        return $this->id;
    }
}