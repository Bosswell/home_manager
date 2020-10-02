<?php

namespace Tests\Exam;

use App\Exam\QuestionSnippetNormalizer;
use PHPUnit\Framework\TestCase;


class QuestionSnippetNormalizerTest extends TestCase
{
    public function testNormalize(): void
    {
        $normalizer = new QuestionSnippetNormalizer();
        $snippet = $normalizer->normalize([
            'questionId' => '1',
            'correctOptions' => '1, 2',
            'nbOptions' => '5'
        ]);

        $this->assertEquals(5, $snippet->getNbOptions());
        $this->assertEquals([1, 2], $snippet->getCorrectOptions());
    }

    public function testNormalizeArray(): void
    {
        $normalizer = new QuestionSnippetNormalizer();
        $snippets = $normalizer->normalizeArray([
            [
                'questionId' => '1',
                'correctOptions' => '1, 2',
                'nbOptions' => '5'
            ],
            [
                'questionId' => '2',
                'correctOptions' => '1, 2',
                'nbOptions' => '3'
            ],
        ]);

        $this->assertEquals(5, $snippets[1]->getNbOptions());
        $this->assertEquals([1, 2], $snippets[1]->getCorrectOptions());

        $this->assertEquals(3, $snippets[2]->getNbOptions());
        $this->assertEquals([1, 2], $snippets[2]->getCorrectOptions());
    }
}