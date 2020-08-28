<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserFixtures extends Fixture
{
    private UserPasswordEncoderInterface $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setFullName('Jakub Batko');
        $user->setPlainPassword('zaq1@WSX');
        $user->setRoles(['ROLE_USER']);
        $user->setEmail('jakub@home.pl');

        $user->setPassword(
            $this->encoder->encodePassword($user, 'zaq1@WSX')
        );

        $manager->persist($user);

        $manager->flush();
    }
}
