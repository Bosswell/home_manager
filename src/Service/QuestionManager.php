<?php

namespace App\Service;

use App\ApiException;
use App\Entity\Question;
use App\Entity\Recipe;
use App\Message\Recipe\CreateRecipeMessage;
use App\Message\Recipe\UpdateRecipeMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;


class QuestionManager
{
    private EntityManagerInterface $em;
    private ObjectValidator $validator;
    private ?TokenInterface $token;

    public function __construct(EntityManagerInterface $entityManager, ObjectValidator $validator, TokenStorageInterface $storage)
    {
        $this->em = $entityManager;
        $this->validator = $validator;
        $this->token = $storage->getToken();
    }

    /**
     * @param CreateRecipeMessage $message
     * @throws ApiException
     */
    public function createRecipe(CreateRecipeMessage $message): void
    {
        $transaction = new Question(
            $message->getName(),
            $message->getContent(),
            $this->token->getUser()
        );

        $this->validator->validate($transaction);
        $this->em->persist($transaction);
        $this->em->flush();
    }

    /**
     * @param UpdateRecipeMessage $message
     * @throws ApiException
     */
    public function updateRecipe(UpdateRecipeMessage $message): void
    {
        $user = $this->token->getUser();
        /** @var Recipe $recipe */
        $recipe = $this->em
            ->getRepository(Recipe::class)
            ->findOneBy(['id' => $message->getId(), 'user' => $user]);

        if (is_null($recipe)) {
            throw ApiException::entityNotFound(
                $message->getId(),
                get_class($this),
                ['Recipe that you try to update does not exists']
            );
        }

        $recipe->update($message->getName(), $message->getContent());
        $this->validator->validate($recipe);
        $this->em->flush();
    }

    /**
     * @throws ApiException
     */
    public function deleteRecipe(int $id)
    {
        $user = $this->token->getUser();
        $recipe = $this->em
            ->getRepository(Recipe::class)
            ->findOneBy(['id' => $id, 'user' => $user]);

        if (is_null($recipe)) {
            throw ApiException::entityNotFound(
                $id,
                get_class($this),
                ['Recipe that you try to update does not exists']
            );
        }

        $recipe->delete();
        $this->em->flush();
    }
}