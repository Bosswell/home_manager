<?php


namespace App\Message\Exam\Model;


class UserQuestionSnippet
{
    private int $questionId;

    /** @var int[] */
    private array $checkedOptions;

    public function __construct(?array $data = null)
    {
        $this->questionId = $data['questionId'] ?? 0;
        $this->checkedOptions = $data['checkedOptions'] ?? [];
    }

    public function getQuestionId(): int
    {
        return $this->questionId;
    }

    public function getCheckedOptions(): array
    {
        return $this->checkedOptions;
    }
}