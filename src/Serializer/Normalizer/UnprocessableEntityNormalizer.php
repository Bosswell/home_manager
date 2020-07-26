<?php

namespace App\Serializer\Normalizer;

use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UnprocessableEntityNormalizer implements NormalizerInterface
{
    public function normalize($exception, string $format = null, array $context = [])
    {
        return json_decode($exception->getMessage());
    }

    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof UnprocessableEntityHttpException;
    }
}
