<?php

namespace App\Serializer\Normalizer;

use App\ApiException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


class ApiExceptionNormalizer implements NormalizerInterface
{
    /**
     * @param ApiException $exception
     * @param string|null $format
     * @param array $context
     * @return array
     */
    public function normalize($exception, string $format = null, array $context = [])
    {
        return $exception->getErrors();
    }

    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof ApiException;
    }
}
