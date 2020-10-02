<?php

namespace Tests\Exam;

use App\Exam\ExamValidator;
use App\Message\Exam\ValidateExamMessage;
use PHPUnit\Framework\TestCase;


class ExamValidatorTest extends TestCase
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
                'correctOptions' => [
                    1, 2
                ],
                'optionsNb' => 5
            ],
            [
                'questionId' => 2,
                'correctOptions' => [
                    1, 4
                ],
                'optionsNb' => 3
            ],
            [
                'questionId' => 3,
                'correctOptions' => [
                    1
                ],
                'optionsNb' => 2
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
}