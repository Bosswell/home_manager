<?php

namespace App\Controller;

use App\ApiController;
use App\ApiException;
use App\Http\ApiResponse;
use App\Message\Recipe\CreateRecipeMessage;
use App\Message\Recipe\UpdateRecipeMessage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecipeController extends ApiController
{
    /**
     * @Route("/recipe", name="create_recipe", methods={"POST"})
     * @ParamConverter("message", class=CreateRecipeMessage::class, converter="message_converter")
     * @throws ApiException
     */
    public function createTransactionAction(CreateRecipeMessage $message)
    {
        $this->recipeService->createRecipe($message);

        return new ApiResponse('Recipe has been successfully created', Response::HTTP_CREATED);
    }

    /**
     * @Route("/recipe/delete/{id}", name="delete_recipe", methods={"DELETE"})
     * @throws ApiException
     */
    public function deleteTransactionAction(string $id)
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
    public function updateTransactionAction(UpdateRecipeMessage $message)
    {
        $this->recipeService->updateRecipe($message);

        return new ApiResponse(
            'Recipe has been successfully updated',
            Response::HTTP_CREATED
        );
    }
}