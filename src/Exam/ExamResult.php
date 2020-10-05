<?php


namespace App\Exam;


class ExamResult
{
    private int $totalPoints;
    private int $correctPoints;
    private int $incorrectPoints;
    private float $percentage;
    private array $correctOptions = [];

    public function __construct(int $correctPoints, int $totalPoints, int $incorrectPoints, float $percentage)
    {
        $this->correctPoints = $correctPoints;
        $this->totalPoints = $totalPoints;
        $this->incorrectPoints = $incorrectPoints;
        $this->percentage = round($percentage, 2);
    }

    public function setCorrectOptions($correctOptions): void
    {
        $this->correctOptions = $correctOptions;
    }

    public function toArray(): array
    {
        return [
            'totalPoints'       => $this->totalPoints,
            'correctPoints'     => $this->correctPoints,
            'incorrectPoints'   => $this->incorrectPoints,
            'percentage'        => $this->percentage,
            'correctOptions'    => $this->correctOptions,
        ];
    }
}