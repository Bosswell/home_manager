<?php

namespace Tests\Exam;

use App\Exam\AbstractExamValidator;
use App\Exam\StandardValidator;
use App\Message\Exam\Model\UserQuestionSnippet;
use PHPUnit\Framework\TestCase;


class StandardExamValidatorTest extends TestCase
{
    private static AbstractExamValidator $examValidator;

    public static function setUpBeforeClass(): void
    {
        $examValidator = new StandardValidator();
        $examValidator
            ->setUserQuestionsSnippets([
                new UserQuestionSnippet(['questionId' => 1, 'checkedOptions' => [2, 5]]),
                new UserQuestionSnippet(['questionId' => 2, 'checkedOptions' => [2, 3]]),
                new UserQuestionSnippet(['questionId' => 5, 'checkedOptions' => [1]]),
            ])
            ->setCorrectOptions([
                1 => [1, 2],
                2 => [1, 4, 2, 3],
                5 => [1],
            ]);

        self::$examValidator = $examValidator;
    }

    public function testGetExamResult(): void
    {
        $result = self::$examValidator
            ->validate()
            ->toArray();

        $this->assertEquals(3, $result['totalPoints']);
        $this->assertEquals(1, $result['correctPoints']);
        $this->assertEquals(2, $result['incorrectPoints']);
        $this->assertEquals(33.33, $result['percentage']);
    }
}