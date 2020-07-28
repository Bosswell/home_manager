<?php

namespace App\Controller;

use App\ApiController;
use App\Entity\Transaction;
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
        $em = $this
            ->getDoctrine()
            ->getManager();

        /** @var TransactionType|null $transactionType */
        $transactionType = $em
            ->getRepository(TransactionType::class)
            ->find($message->getTransactionTypeId());

        if (is_null($transactionType)) {
            throw new \InvalidArgumentException('Invalid transactionTypeId value', Response::HTTP_BAD_REQUEST);
        }

        $transaction = new Transaction(
            $message->getAmount(),
            $transactionType
        );

        $this->validator->validate($transaction);

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
}
