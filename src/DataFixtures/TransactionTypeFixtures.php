<?php

namespace App\DataFixtures;

use App\Entity\TransactionType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TransactionTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $manager->persist(new TransactionType('Artykuły spożywcze'));
        $manager->persist(new TransactionType('Paliwo'));
        $manager->persist(new TransactionType('Kosmetyki'));
        $manager->persist(new TransactionType('Rozrywka'));

        $manager->flush();
    }
}
