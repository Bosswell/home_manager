<?php

namespace App\Controller;

use App\ApiController;
use App\Entity\TransactionType;
use App\Http\ApiResponse;
use App\Message\CreateTransactionMessage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class TransactionController extends ApiController
{
    /**
     * @Route("/transaction", name="create_transaction", methods={"POST"})
     * @ParamConverter("createTransaction", class="App\Message\CreateTransactionMessage", converter="message_converter")
     */
    public function createAction(CreateTransactionMessage $message)
    {
        $this->transactionFacade->createTransaction($message);

        return new ApiResponse('Transaction has been successfully created', Response::HTTP_CREATED);
    }

    /**
     * @Route("/transaction", name="get_transaction", methods={"GET"})
     */
    public function getAction()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/TransactionController.php',
        ]);
    }

    /**
     * @Route("/transaction/types/list", name="list_transaction_types", methods={"GET"})
     */
    public function listTypesAction()
    {
        $transactionTypes = $this
            ->getDoctrine()
            ->getRepository(TransactionType::class)
            ->findAll()
        ;

        return new ApiResponse('', Response::HTTP_CREATED, $transactionTypes);
    }
}
