<?php

namespace App\Message\Exam;


class UpdateExamMessage extends CreateExamMessage
{
    private int $id = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }
}