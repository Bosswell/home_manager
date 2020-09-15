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
        $user->setFullName('John Doe');
        $user->setPlainPassword('demo1234');
        $user->setRoles(['ROLE_USER']);
        $user->setEmail('demo@demo.com');

        $user->setPassword(
            $this->encoder->encodePassword($user, 'demo1234')
        );

        $manager->persist($user);

        $manager->flush();
    }
}
