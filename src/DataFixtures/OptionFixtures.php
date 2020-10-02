<?php


namespace App\DataFixtures;

use App\Entity\Exam;
use App\Entity\Option;
use App\Entity\Question;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


class OptionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('pl');

        /** @var Exam $exams */
        $questions = $manager
            ->getRepository(Question::class)
            ->findAll();

        /** @var Question $question */
        foreach ($questions as $question) {
            for ($i = 0; $i < rand(5, 7); $i++) {
                $option = new Option($faker->text(200), $i == 0 ? true : (bool)rand(0, 1));
                $manager->persist($option);
                $question->addOption($option);
            }

            $manager->flush();
        }
    }

    public function getDependencies()
    {
        return array(
            QuestionFixtures::class
        );
    }
}