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
    private string $name = 't.id';

    /**
     * @Assert\Choice({"ASC", "DESC"})
     */
    private string $direction = 'DESC';

    public function getName(): string
    {
        return $this->name;
    }

    public function getDirection(): string
    {
        return $this->direction;
    }
}
