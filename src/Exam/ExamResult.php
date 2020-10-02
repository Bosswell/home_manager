<?php


namespace App\Exam;


class ExamResult
{
    private int $totalPoints;
    private int $correctPoints;
    private int $incorrectPoints;
    private int $percentage;

    /** @var QuestionSnippet[] */
    private array $questionsSnippets;

    public function __construct(int $correctPoints, int $totalPoints)
    {
        $this->correctPoints = $correctPoints;
        $this->totalPoints = $totalPoints;
        $this->incorrectPoints = $totalPoints - $correctPoints;
        $this->percentage = $this->totalPoints ? round($this->correctPoints / $this->totalPoints * 100, 2) : 0;
    }

    public function setQuestionsSnippets($questionsSnippets): void
    {
        $this->questionsSnippets = $questionsSnippets;
    }

    public function toArray(): array
    {
        return [
            'totalPoints'       => $this->totalPoints,
            'correctPoints'     => $this->correctPoints,
            'inCorrectPoint'    => $this->incorrectPoints,
            'percentage'        => $this->percentage,
            'questionsSnippets' => $this->questionsSnippets ?? [],
        ];
    }
}