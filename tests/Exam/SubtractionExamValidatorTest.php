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
            ->setUserQuestionsSnippets([
                new UserQuestionSnippet(['questionId' => 1, 'checkedOptions' => [2, 5]]),
                new UserQuestionSnippet(['questionId' => 2, 'checkedOptions' => [2, 3]]),
                new UserQuestionSnippet(['questionId' => 3, 'checkedOptions' => [1]]),
            ])
            ->validate()
            ->toArray();

        $this->assertEquals(7, $result['totalPoints']);
        $this->assertEquals(4, $result['correctPoints']);
        $this->assertEquals(1, $result['incorrectPoints']);
        $this->assertEquals(42.86, $result['percentage']);
    }

    public function testGetExamResultWithMoreIncorrectThenCorrect(): void
    {
        $result = self::$examValidator
            ->setUserQuestionsSnippets([
                new UserQuestionSnippet(['questionId' => 1, 'checkedOptions' => [5, 4, 5, 6, 7]]),
                new UserQuestionSnippet(['questionId' => 2, 'checkedOptions' => [2, 3, 5, 8]]),
                new UserQuestionSnippet(['questionId' => 3, 'checkedOptions' => [2]]),
            ])
            ->validate()
            ->toArray();

        $this->assertEquals(7, $result['totalPoints']);
        $this->assertEquals(2, $result['correctPoints']);
        $this->assertEquals(8, $result['incorrectPoints']);
        $this->assertEquals(0, $result['percentage']);
    }
}