<?php

namespace App\Message\Exam;


class CreateExamMessage
{
    private string $name;
    private string $code;

    public function __construct(?array $data = null)
    {
        $this->name = $data['name'] ?? '';
        $this->code = $data['code'] ?? '';
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