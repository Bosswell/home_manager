<?php


namespace App\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class NormalizerFactory
{
    /** @var iterable */
    private iterable $normalizers;

    public function __construct(iterable $normalizers)
    {
        $this->normalizers = $normalizers;
    }

    public function getNormalizer($data)
    {
        foreach ($this->normalizers as $normalizer) {
            if ($normalizer instanceof NormalizerInterface && $normalizer->supportsNormalization($data)) {
                return $normalizer;
            }
        }

        return null;
    }
}