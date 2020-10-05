<?php


namespace App\Exam;


class CorrectOptionsNormalizer
{
    public function normalize(array $option): array
    {
        if (
            !key_exists('id', $option) ||
            !key_exists('correctOptions', $option)
        ) {
            throw new \InvalidArgumentException('Invalid correct options signature');
        }

        return array_map('intval', explode(',', $option['correctOptions']));
    }


    public function normalizeArray(array $correctOptions): array
    {
        $normalizedCorrectOptions = [];

        foreach ($correctOptions as $option) {
            $normalizedCorrectOptions[(int)$option['id']] = $this->normalize($option);
        }

        return $normalizedCorrectOptions;
    }
}