<?php

namespace App\Message\ExamHistory\Model;

use DateTime;


class ExamHistoryListFilterBy
{
    private ?string $username;
    private ?int $userNumber;
    private ?bool $isActive;
    private \DateTime $startDate;

    public function __construct(?array $data = null)
    {
        $this->username = $data['username'] ?? null;
        $this->userNumber = $data['userNumber'] ?? null;
        $this->isActive = $data['isActive'] ?? null;

        try {
            $this->startDate = (new DateTime())->setTimestamp($data['dateStart']);
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
}