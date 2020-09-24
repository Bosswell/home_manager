<?php

namespace App;

use App\Service\RecipeService;
use App\Service\TransactionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


abstract class ApiController extends AbstractController
{
    protected TransactionService $transactionService;
    protected RecipeService $recipeService;

    public function __construct(TransactionService $transactionService, RecipeService $recipeService)
    {
        $this->transactionService = $transactionService;
        $this->recipeService = $recipeService;
    }
}
