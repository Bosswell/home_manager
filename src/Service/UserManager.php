<?php

namespace App\Service;

use App\Entity\User;
use App\Message\CreateUserMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager
{
    private EntityManagerInterface $em;
    private ObjectValidator $validator;
    private UserPasswordEncoderInterface $encoder;

    public function __construct(EntityManagerInterface $entityManager, ObjectValidator $validator, UserPasswordEncoderInterface $encoder)
    {
        $this->em = $entityManager;
        $this->validator = $validator;
        $this->encoder = $encoder;
    }

    public function createUser(CreateUserMessage $message): void
    {
        $user = new User();
        $user
            ->setEmail($message->getEmail())
            ->setRoles(['ROLE_USER'])
            ->setConfirmPlainPassword($message->getConfirmPassword())
            ->setPlainPassword($message->getPassword());

        $this->validator->validate($user);
        $user->setPassword(
            $this->encoder->encodePassword($user, $message->getPassword())
        );

        $this->em->persist($user);
        $this->em->flush();
    }
}
