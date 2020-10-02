<?php

namespace Tests\Exam;

use App\Entity\Exam;
use App\Entity\Question;
use App\Exam\ExamValidator;
use App\Message\Exam\ValidateExamMessage;
use PHPUnit\Framework\TestCase;
use Tests\FunctionalTestCase;


class ExamValidatorTest extends FunctionalTestCase
{
    private static ExamValidator $examValidator;

    public static function setUpBeforeClass(): void
    {
        $testExam = [
            'questions' => [
                [
                    'questionId' => 1,
                    'checkedOptions' => [
                        2, 5
                    ]
                ],
                [
                    'questionId' => 2,
                    'checkedOptions' => [
                        2, 3
                    ]
                ],
                [
                    'questionId' => 3,
                    'checkedOptions' => [
                        1
                    ]
                ]
            ]
        ];

        $correctOptions = [
            [
                'questionId' => 1,
                'correctOptions' => '1, 2',
                'nbOptions' => 5
            ],
            [
                'questionId' => 2,
                'correctOptions' => '1, 4',
                'nbOptions' => 3
            ],
            [
                'questionId' => 3,
                'correctOptions' => '1',
                'nbOptions' => 2
            ]
        ];

        $examValidator = new ExamValidator();

        $examValidator
            ->setExam(new ValidateExamMessage($testExam))
            ->setCorrectQuestions($correctOptions)
            ->validate();

        self::$examValidator = $examValidator;
    }

    public function testGetTotalPoints(): void
    {
        $this->assertEquals(10, self::$examValidator->getTotalPoints());
    }

    public function testGetCorrectPoints(): void
    {
        $this->assertEquals(2, self::$examValidator->getCorrectPoints());
    }

    public function testGetInCorrectPoints(): void
    {
        $this->assertEquals(8, self::$examValidator->getInCorrectPoints());
    }

    public function testSetCorrectQuestionsQuestionIdFailure(): void
    {
        $validator = new ExamValidator();

        $this->expectException(\InvalidArgumentException::class);
        $validator->setCorrectQuestions([
            [
                'question' => 3,
                'correctOptions' => '1',
                'nbOptions' => 2
            ]
        ]);
    }

    public function testSetCorrectQuestionsCorrectOptionsFailure(): void
    {
        $validator = new ExamValidator();

        $this->expectException(\InvalidArgumentException::class);
        $validator->setCorrectQuestions([
            [
                'questionId' => 3,
                'correctOption' => '1',
                'nbOptions' => 2
            ]
        ]);
    }

    public function testSetCorrectQuestionsNbOptionsFailure(): void
    {
        $validator = new ExamValidator();

        $this->expectException(\InvalidArgumentException::class);
        $validator->setCorrectQuestions([
            [
                'questionId' => 3,
                'correctOptions' => '1',
                'options' => 2
            ]
        ]);
    }
}