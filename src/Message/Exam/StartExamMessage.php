<?php

namespace App\Message\Exam;


class StartExamMessage
{
    private int $examId;
    private string $code;
    private string $username;
    private ?int $userNumber;

    public function __construct(?array $data = null)
    {
        $this->examId = $data['examId'] ?? 0;
        $this->code = $data['code'] ?? '';
        $this->username = $data['username'] ?? '';
        $this->userNumber = $data['userNumber'] ?? null;
    }

    public function getExamId(): int
    {
        return $this->examId;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->username;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getUserNumber(): ?int
    {
        return $this->userNumber;
    }

    public function setExamId($examId): void
    {
        $this->examId = (int)$examId;
    }

    public function setCode($code): void
    {
        $this->code = (string)$code;
    }

    public function setUsername($username): void
    {
        $this->username = (string)$username;
    }
    
    public function setUserNumber($userNumber): void
    {
        $this->userNumber = (int)$userNumber;
    }
}