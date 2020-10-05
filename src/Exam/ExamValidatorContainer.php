<?php

namespace App\Exam;

use App\ApiException;
use Symfony\Component\DependencyInjection\ServiceLocator;


final class ExamValidatorContainer
{
    private ServiceLocator $locator;

    public function __construct(ServiceLocator $locator)
    {
        $this->locator = $locator;
    }

    /**
     * @throws ApiException
     */
    public function getValidator(string $mode): AbstractExamValidator
    {
        if ($this->locator->has($mode)) {
            /** @var AbstractExamValidator $validator */
            $validator = $this->locator->get($mode);

            return $validator;
        }

        throw new ApiException(sprintf('Exam validator with [ mode = %s ] does not exist', $mode), 0);
    }
}
