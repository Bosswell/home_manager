<?php


namespace App\DataFixtures;

use App\Entity\Exam;
use App\Entity\Question;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


class QuestionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('pl');

        /** @var Exam $exams */
        $exams = $manager
            ->getRepository(Exam::class)
            ->findAll();

        foreach ($exams as $exam) {
            for ($i = 0; $i < 5; $i++) {
                $question = new Question($faker->text(200));
                $manager->persist($question);
                $question->setExam($exam);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            ExamFixtures::class
        );
    }
}