<?php

namespace App\Message\Exam;


class CreateExamMessage
{
    protected string $name;
    protected string $code;
    protected string $mode;
    protected bool $hasVisibleMode;

    public function __construct(?array $data = null)
    {
        $this->name = $data['name'] ?? '';
        $this->code = $data['code'] ?? '';
        $this->mode = $data['mode'] ?? '';
        $this->hasVisibleMode = $data['hasVisibleMode'] ?? '';
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function setCode($code): void
    {
        $this->code = $code;
    }

    public function getMode(): string
    {
        return $this->mode;
    }

    public function setMode($mode): void
    {
        $this->mode = $mode;
    }

    public function getHasVisibleMode(): bool
    {
        return $this->hasVisibleMode;
    }

    public function setHasVisibleMode($hasVisibleMode): void
    {
        $this->hasVisibleMode = $hasVisibleMode;
    }
}