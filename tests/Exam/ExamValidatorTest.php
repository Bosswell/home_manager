<?php

namespace Tests\Exam;

use App\Exam\ExamValidator;
use App\Exam\QuestionSnippet;
use App\Message\Exam\Model\UserQuestionSnippet;
use PHPUnit\Framework\TestCase;


class ExamValidatorTest extends TestCase
{
    private static ExamValidator $examValidator;

    public static function setUpBeforeClass(): void
    {
        $examValidator = new ExamValidator();
        $examValidator
            ->setUserQuestionsSnippets([
                new UserQuestionSnippet(['questionId' => 1, 'checkedOptions' => [2, 5]]),
                new UserQuestionSnippet(['questionId' => 2, 'checkedOptions' => [2, 3]]),
                new UserQuestionSnippet(['questionId' => 3, 'checkedOptions' => [1]]),
            ])
            ->setQuestionSnippets([
                1 => new QuestionSnippet(5, [1, 2]),
                2 => new QuestionSnippet(3, [1, 4]),
                3 => new QuestionSnippet(2, [1]),
            ])
            ->validate();

        self::$examValidator = $examValidator;
    }

    public function testGetExamResult(): void
    {
        $result = self::$examValidator->getExamResult()->toArray();

        $this->assertEquals(10, $result['totalPoints']);
        $this->assertEquals(2, $result['correctPoints']);
        $this->assertEquals(8, $result['inCorrectPoint']);
        $this->assertEquals(20, $result['percentage']);
    }
}