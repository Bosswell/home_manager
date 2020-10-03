<?php

namespace Tests\Exam;

use App\Exam\AbstractExamValidator;
use App\Exam\SubtractionExamValidator;
use App\Message\Exam\Model\UserQuestionSnippet;
use PHPUnit\Framework\TestCase;


class SubtractionExamValidatorTest extends TestCase
{
    private static AbstractExamValidator $examValidator;

    public static function setUpBeforeClass(): void
    {
        $examValidator = new SubtractionExamValidator();
        $examValidator
            ->setUserQuestionsSnippets([
                new UserQuestionSnippet(['questionId' => 1, 'checkedOptions' => [2, 5]]),
                new UserQuestionSnippet(['questionId' => 2, 'checkedOptions' => [2, 3]]),
                new UserQuestionSnippet(['questionId' => 3, 'checkedOptions' => [1]]),
            ])
            ->setCorrectOptions([
                1 => [1, 2],
                2 => [1, 4, 2, 3],
                3 => [1],
            ]);

        self::$examValidator = $examValidator;
    }

    public function testGetExamResult(): void
    {
        $result = self::$examValidator
            ->validate()
            ->toArray();

        $this->assertEquals(7, $result['totalPoints']);
        $this->assertEquals(4, $result['correctPoints']);
        $this->assertEquals(1, $result['inCorrectPoints']);
        $this->assertEquals(42.86, $result['percentage']);
    }
}