<?php

namespace App\Message\Exam\Model;

use Symfony\Component\Validator\Constraints as Assert;


class ExamListSortBy
{
    /**
     * @Assert\Choice(
     *     {"e.id", "e.name", "e.created_at"},
     *     message="Invalid sorting name value."
     *)
     */
    private string $name;

    /**
     * @Assert\Choice(
     *     {"asc", "desc"},
     *     message="Invalid sorting direction value."
     * )
     */
    private string $direction;

    public function __construct(?array $data = null)
    {
        $this->name = $data['name'] ?? 'e.created_at';
        $this->direction = $data['direction'] ?? 'desc';
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDirection(): string
    {
        return $this->direction;
    }
}
