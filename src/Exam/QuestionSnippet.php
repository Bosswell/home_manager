<?php


namespace App\Exam;


class QuestionSnippet implements \JsonSerializable
{
    private int $nbOptions;
    private array $correctOptions;

    public function __construct(int $nbOptions, array $correctOptions)
    {
        $this->nbOptions = $nbOptions;
        $this->correctOptions = $correctOptions;
    }

    public function getNbOptions(): int
    {
        return $this->nbOptions;
    }

    public function getCorrectOptions(): array
    {
        return $this->correctOptions;
    }

    public function jsonSerialize()
    {
        return [
            'correctOptions' => $this->correctOptions,
            'nbOptions' => $this->nbOptions,
        ];
    }
}