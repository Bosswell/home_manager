<?php

namespace App\DataFixtures;

use App\Entity\Recipe;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class RecipeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('pl');

        /** @var User $user */
        $user = $manager
            ->getRepository(User::class)
            ->findOneBy(['email' => 'demo@demo.com']);

        for ($i = 0; $i < 30; $i++) {
            $manager->persist(new Recipe($faker->name, $faker->text(400), $user));
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class
        );
    }
}
