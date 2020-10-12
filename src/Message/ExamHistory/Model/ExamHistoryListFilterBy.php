<?php

namespace App\Message\ExamHistory\Model;

use DateTime;


class ExamHistoryListFilterBy
{
    private ?string $username;
    private ?int $userNumber;
    private ?int $examId;
    private ?string $userGroup;
    private ?bool $isActive;
    private \DateTime $startDate;

    public function __construct(?array $data = null)
    {
        $this->username = $data['username'] ?? null;
        $this->userNumber = $data['userNumber'] ?? null;
        $this->examId = $data['examId'] ?? null;
        $this->isActive = $data['isActive'] ?? null;
        $this->userGroup = $data['userGroup'] ?? null;

        try {
            $this->startDate = new DateTime($data['dateStart']);
        } catch (\Throwable $ex) {
            $this->startDate = new DateTime('first day of this month');
        }
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getUserNumber(): ?string
    {
        return $this->userNumber;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function getStartDate(): DateTime
    {
        return $this->startDate;
    }

    public function getUserGroup(): ?string
    {
        return $this->userGroup;
    }

    public function getExamId(): ?int
    {
        return $this->examId;
    }
}