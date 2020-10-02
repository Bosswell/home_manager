<?php


namespace App\DataFixtures;

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

        /** @var Question[] $questions */
        $questions = $manager->getRepository(Question::class)
            ->findAll();

        for ($i = 0; $i < 35; $i++) {
            $option = new Option($faker->text(200), (bool)rand(0, 1));
            $manager->persist($option);

            $option->setQuestion($questions[$i % 5]);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            ExamFixtures::class,
            QuestionFixtures::class
        );
    }
}