<?php

namespace App\Message\Exam;


class UpdateExamMessage extends CreateExamMessage
{
    private int $id;

    public function __construct(?array $data = null)
    {
        $this->id = $data['id'] ?? '';
        parent::__construct($data);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }
}