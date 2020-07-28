<?php

namespace App\DataFixtures;

use App\Entity\Transaction;
use App\Entity\TransactionType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TransactionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $transactionTypes = $manager
            ->getRepository(TransactionType::class)
            ->findAll();

        /** @var TransactionType $transactionType */
        foreach ($transactionTypes as $transactionType) {
            for ($i = 0; $i < 20; $i++) {
                $transaction = new Transaction(rand(5, 1000), $transactionType);
                $manager->persist($transaction);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            TransactionTypeFixtures::class,
        );
    }
}