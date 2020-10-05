<?php

namespace Tests\Exam;

use App\Exam\CorrectOptionsNormalizer;
use PHPUnit\Framework\TestCase;


class CorrectOptionsNormalizerTest extends TestCase
{
    public function testNormalize(): void
    {
        $normalizer = new CorrectOptionsNormalizer();
        $snippet = $normalizer->normalize([
            'id' => '1',
            'correctOptions' => '1, 2',
        ]);

        $this->assertEquals([1, 2], $snippet);
    }

    public function testNormalizeArray(): void
    {
        $normalizer = new CorrectOptionsNormalizer();
        $snippets = $normalizer->normalizeArray([
            [
                'id' => '1',
                'correctOptions' => '1, 2'
            ],
            [
                'id' => '2',
                'correctOptions' => '3, 2'
            ],
        ]);

        $this->assertEquals([1, 2], $snippets[1]);
        $this->assertEquals([3, 2], $snippets[2]);
    }
}