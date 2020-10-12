<?php

namespace App\Message\ExamHistory;

use DateTime;


class ExamHistoryListFilterBy
{
    private ?string $username;
    private ?int $userNumber;
    private ?bool $isActive;
    private \DateTime $startDate;
    private \DateTime $endDate;

    public function __construct(?array $data = null)
    {
        $this->username = $data['username'] ?? null;
        $this->userNumber = $data['userNumber'] ?? null;
        $this->isActive = $data['isActive'] ?? null;

        try {
            $this->startDate = DateTime::createFromFormat('d.m.Y', $data['startDate']);
        } catch (\Throwable $ex) {
            $this->startDate = new DateTime('first day of this month');
        }

        try {
            $this->endDate = DateTime::createFromFormat('d.m.Y', $data['endDate']);
        } catch (\Throwable $ex) {
            $this->endDate = new DateTime();
        }
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getUserNumber(): string
    {
        return $this->userNumber;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function getStartDate(): DateTime
    {
        return $this->startDate;
    }

    public function getEndDate(): DateTime
    {
        return $this->endDate;
    }
}