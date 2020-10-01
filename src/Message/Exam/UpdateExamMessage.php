<?php

namespace App\Message\Exam;


class UpdateExamMessage
{
    private int $id;
    private string $name;
    private string $code;

    public function __construct(?array $data = null)
    {
        $this->id = $data['id'] ?? 0;
        $this->name = $data['name'] ?? '';
        $this->code = $data['code'] ?? '';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = (string)$name;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode($code): void
    {
        $this->code = $code;
    }
}