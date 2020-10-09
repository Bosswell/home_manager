<?php


namespace App\Message\Option;


class UpdateOptionMessage extends CreateOptionMessage
{
    private int $optionId = 0;

    public function getOptionId(): int
    {
        return $this->optionId;
    }

    public function setOptionId($optionId): void
    {
        $this->optionId = (int)$optionId;
    }
}