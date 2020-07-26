<?php


namespace App\Service;


use App\Entity\User;
use App\Message\CreateUserMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

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

    public function createUser(CreateUserMessage $message)
    {
        $user = new User();
        $user
            ->setEmail($message->getEmail())
            ->setPassword(
                $this->encoder->encodePassword($user, $message->getPassword())
            )
            ->setRoles(['ROLE_USER'])
            ->setConfirmPlainPassword($message->getConfirmPassword())
            ->setPlainPassword($message->getPassword());

        $this->validator->validate($user);

        $this->em->persist($user);
        $this->em->flush();
    }

    private function getErrorMessagesFromViolations(ConstraintViolationListInterface $violations): array
    {
        /** @var ConstraintViolationInterface $violation */
        foreach ($violations as $violation) {
            $errorMessages[] = $violation->getMessage();
        }

        return $errorMessages ?? [];
    }
}