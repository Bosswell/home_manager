<?php


namespace App\DataFixtures;

use App\Entity\Exam;
use App\Entity\Question;
use App\Entity\User;
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

        /** @var User $user */
        $user = $manager
            ->getRepository(User::class)
            ->findOneBy(['email' => 'demo@demo.com']);

        /** @var Exam $exam */
        foreach ($exams as $exam) {
            for ($i = 0; $i < 5; $i++) {
                $question = new Question($faker->text(200), $user);
                $manager->persist($question);
                $exam->addQuestion($question);
            }

            $manager->flush();
        }
    }

    public function getDependencies()
    {
        return array(
            ExamFixtures::class,
            UserFixtures::class
        );
    }
}