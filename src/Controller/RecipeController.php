<?php

namespace App\Controller;

use App\ApiController;
use App\ApiException;
use App\Entity\User;
use App\Factory\PagerfantaFactory;
use App\Http\ApiResponse;
use App\Message\Recipe\CreateRecipeMessage;
use App\Message\Recipe\ListRecipesMessage;
use App\Message\Recipe\UpdateRecipeMessage;
use App\Repository\RecipeRepository;
use App\Service\ObjectValidator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecipeController extends ApiController
{
    /**
     * @Route("/recipe/{id}", name="get_recipe", methods={"GET"})
     * @throws ApiException
     */
    public function getRecipeAction(string $id, RecipeRepository $recipeRepository)
    {
        $recipe = $recipeRepository->findOneBy(['id' => $id, 'user' => $this->getUser()]);

        if (is_null($recipe)) {
            throw new ApiException(
                'Recipe not found',
                Response::HTTP_NOT_FOUND,
                ['Recipe that you try to get does not exists']
            );
        }

        return new ApiResponse('Recipe has been found', Response::HTTP_OK, $recipe->toArray());
    }

    /**
     * @Route("/recipe", name="create_recipe", methods={"POST"})
     * @ParamConverter("message", class=CreateRecipeMessage::class, converter="message_converter")
     * @throws ApiException
     */
    public function createRecipeAction(CreateRecipeMessage $message)
    {
        $this->recipeService->createRecipe($message);

        return new ApiResponse('Recipe has been successfully created', Response::HTTP_CREATED);
    }

    /**
     * @Route("/recipe/delete/{id}", name="delete_recipe", methods={"DELETE"})
     * @throws ApiException
     */
    public function deleteRecipeAction(string $id)
    {
        $this->recipeService->deleteRecipe((int)$id);

        return new ApiResponse(
            'Recipe has been removed',
            Response::HTTP_OK
        );
    }

    /**
     * @Route("/recipe/update", name="update_recipe", methods={"PUT"})
     * @ParamConverter("message", class=UpdateRecipeMessage::class, converter="message_converter")
     * @throws ApiException
     */
    public function updateRecipeAction(UpdateRecipeMessage $message)
    {
        $this->recipeService->updateRecipe($message);

        return new ApiResponse(
            'Recipe has been successfully updated',
            Response::HTTP_CREATED
        );
    }

    /**
     * @Route("/recipe/action/list", name="list_recipes", methods={"GET"})
     * @ParamConverter("message", class=ListRecipesMessage::class, converter="query_message_converter")
     * @throws ApiException
     */
    public function listRecipesAction(ListRecipesMessage $message, RecipeRepository $repository, ObjectValidator $validator)
    {
        $validator->validate($message);

        /** @var User $user */
        $user = $this->getUser();

        $sortBy = $message->getSortBy();
        $qb = $repository->getRecipesListQuery(
            $user->getId(),
            $message->getSearchBy(),
            $sortBy->getName(),
            $sortBy->getDirection()
        );

        $pagerfanta = PagerfantaFactory::build($qb, 'r');
        $nbPages = $pagerfanta->getNbPages();

        $pagerfanta->setCurrentPage($message->getNbPage() > $nbPages ? $nbPages : $message->getNbPage());

        return new ApiResponse('Found entries', Response::HTTP_OK, [
            'nbPages' => $nbPages,
            'currentPage' => $pagerfanta->getCurrentPage(),
            'results' => $pagerfanta->getCurrentPageResults()
        ]);
    }
}