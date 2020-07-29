<?php

namespace App;

use App\Service\TransactionFacade;
use App\Service\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


abstract class ApiController extends AbstractController
{
    protected TransactionFacade $transactionFacade;

    public function __construct(TransactionFacade $transactionFacade)
    {
        $this->transactionFacade = $transactionFacade;
    }
}
