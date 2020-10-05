<?php

namespace App\Message\Exam;


class CreateExamMessage
{
    protected string $name = '';
    protected string $code = '';
    protected string $mode = '';
    protected bool $hasVisibleResult = false;
    protected bool $isAvailable = true;
    protected int $timeout = 20;

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

    public function hasVisibleResult(): bool
    {
        return $this->hasVisibleResult;
    }

    public function setHasVisibleResult($hasVisibleResult): void
    {
        $this->hasVisibleResult = (bool)$hasVisibleResult;
    }

    public function isAvailable(): bool
    {
        return $this->isAvailable;
    }

    public function setIsAvailable($isAvailable): void
    {
        $this->isAvailable = (bool)$isAvailable;
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }

    public function setTimeout(int $timeout): void
    {
        $this->timeout = $timeout;
    }
}