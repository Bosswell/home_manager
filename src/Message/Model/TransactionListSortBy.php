<?php

namespace App\Message\Model;

use Symfony\Component\Validator\Constraints as Assert;


class TransactionListSortBy
{
    /**
     * @Assert\Choice(
     *     {"t.id", "tt.name", "t.amount", "t.createdAt"},
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
        $this->name = $data['name'] ?? 't.id';
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
