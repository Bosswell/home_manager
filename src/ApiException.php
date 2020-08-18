<?php

namespace App;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class ApiException extends Exception
{
    const INVALID_ENTITY_MESS = 'Invalid entity';
    const ENTITY_NOT_FOUND_MESS = 'Entity with [id = %d] has not been found';

    private array $errors = [];

    public function __construct($message, $code, $errors)
    {
        $this->errors = $errors;

        parent::__construct($message, $code, null);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public static function invalidEntity(array $errors): self
    {
        return new self(
            self::INVALID_ENTITY_MESS,
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $errors
        );
    }

    public static function entityNotFound(int $id, array $errors = []): self
    {
        return new self(
            sprintf(self::ENTITY_NOT_FOUND_MESS, $id),
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $errors
        );
    }
}