<?php

namespace Tests\Controller;

use App\Entity\Recipe;
use Symfony\Component\HttpFoundation\Response;
use Tests\FunctionalTestCase;


class RecipeControllerTest extends FunctionalTestCase
{
    public function testCreateRecipeAction(): void
    {
        $recipeRepository = $this->entityManager->getRepository(Recipe::class);

        $this->client->request('POST', '/recipe', [], [], [], json_encode([
            'name' => 'Delicious cake',
            'content' => 'Example cake recipe'
        ]));
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        $recipe = $recipeRepository->findBy([], ['id' => 'DESC'],1,0)[0];

        $this->assertEquals('Delicious cake', $recipe->getName());
        $this->assertEquals('Example cake recipe', $recipe->getContent());
    }

    public function testUpdateTransaction()
    {
        $recipeRepository = $this->entityManager->getRepository(Recipe::class);
        /** @var Recipe $recipe */
        $recipe = $recipeRepository->findOneBy(['isDeleted' => false, 'user' => $this->testUser]);

        $this->client->request('PUT', '/recipe/update', [], [], [], json_encode([
            'id' => $recipe->getId(),
            'name' => 'Pizza',
            'content' => 'Tasty pizza',
        ]));
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->entityManager->clear();

        /** @var Recipe $recipeAfter */
        $recipeAfter = $recipeRepository->find($recipe->getId());
        $this->assertEquals('Pizza', $recipeAfter->getName());
        $this->assertEquals('Tasty pizza', $recipeAfter->getContent());
    }

    public function testDeleteRecipe()
    {
        $recipeRepository = $this->entityManager->getRepository(Recipe::class);
        /** @var Recipe $recipe */
        $recipe = $recipeRepository->findOneBy(['isDeleted' => false, 'user' => $this->testUser]);

        $this->client->request('DELETE', '/recipe/delete/' . $recipe->getId());
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->entityManager->clear();

        $recipeAfter = $recipeRepository->find($recipe->getId());
        $this->assertEquals(true, $recipeAfter->isDeleted());
    }

    public function testListTRecipesAction(): void
    {
        $this->client->request('GET', '/recipe/list');

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertEmpty($content['errors']);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertArrayHasKey('nbPages', $content['data']);
        $this->assertArrayHasKey('currentPage', $content['data']);
        $this->assertArrayHasKey('results', $content['data']);
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }
}