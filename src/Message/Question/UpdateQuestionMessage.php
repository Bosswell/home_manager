<?php


namespace App\Message\Question;


class UpdateQuestionMessage extends CreateQuestionMessage
{
    private int $id = 0;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = (int)$id;
    }
}