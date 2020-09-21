<?php

namespace App\DataFixtures;

use App\Entity\Transaction;
use App\Entity\TransactionType;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class TransactionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('pl');
        $fakeTaxPercentages = [9, 17, 18, 23];

        $transactionTypes = $manager
            ->getRepository(TransactionType::class)
            ->findAll();

        /** @var User $user */
        $user = $manager
            ->getRepository(User::class)
            ->findOneBy(['email' => 'demo@demo.com']);

        /** @var TransactionType $transactionType */
        foreach ($transactionTypes as $transactionType) {
            for ($i = 0; $i < 20; $i++) {
                $isIncome = random_int(0, 1);

                $transaction = new Transaction(
                    $isIncome,
                    $faker->randomFloat(2, 0, 300),
                    $faker->text(20),
                    !$isIncome ? $fakeTaxPercentages[random_int(0,3)] : null,
                    $transactionType,
                    $user
                );
                $transaction->setCreatedAt($faker->dateTimeBetween('-2 months'));
                $manager->persist($transaction);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            TransactionTypeFixtures::class,
        );
    }
}
