<?php

namespace App\Controller;

use App\Message\CreateTransactionMessage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TransactionController extends AbstractController
{
    /**
     * @Route("/transaction", name="transaction", methods={"POST"})
     * @ParamConverter("createTransaction", class="App\Message\MessageConverter", converter="message_converter")
     */
    public function createTransactionAction(CreateTransactionMessage $message)
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/TransactionController.php',
        ]);
    }

    /**
     * @Route("/transaction", name="transaction", methods={"GET"})
     */
    public function getTransactionAction()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/TransactionController.php',
        ]);
    }
}
