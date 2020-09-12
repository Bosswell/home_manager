<?php

namespace App\Serializer;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ProblemNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class SerializerFactory
{
    private ?SerializerInterface $serializer = null;

    public function getInstance(): SerializerInterface
    {
        if ($this->serializer !== null) {
            return $this->serializer;
        }

        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer(), new ProblemNormalizer()];
        $this->serializer = new Serializer($normalizers, $encoders);

        return $this->serializer;
    }
}
