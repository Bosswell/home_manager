<?php

namespace App\Message\Transaction;

use DateTime;


class GetTransactionSummaryMessage
{
    private DateTime $startDate;
    private DateTime $endDate;

    public function __construct(?array $data = null)
    {
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

    public function getStartDate(): DateTime
    {
        return $this->startDate;
    }

    public function getEndDate(): DateTime
    {
        return $this->endDate;
    }
}