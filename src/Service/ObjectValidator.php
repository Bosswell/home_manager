<?php


namespace App\Service;


use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ObjectValidator
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate(object $object)
    {
        $violations = $this->validator->validate($object);

        if ($violations->count() !== 0) {
            throw new UnprocessableEntityHttpException(
                json_encode($this->getErrorMessagesFromViolations($violations))
            );
        }
    }

    private function getErrorMessagesFromViolations(ConstraintViolationListInterface $violations): array
    {
        /** @var ConstraintViolationInterface $violation */
        foreach ($violations as $violation) {
            $errorMessages[] = $violation->getMessage();
        }

        return $errorMessages ?? [];
    }
}