<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RecipeController extends AbstractController
{
//    /**
//     * @Route("/transaction", name="create_transaction", methods={"POST"})
//     * @ParamConverter("message", class="App\Message\CreateTransactionMessage", converter="message_converter")
//     * @throws ApiException
//     */
//    public function createTransactionAction(CreateTransactionMessage $message)
//    {
//        $this->transactionFacade->createTransaction($message);
//
//        return new ApiResponse('Transaction has been successfully created', Response::HTTP_CREATED);
//    }
//
//    /**
//     * @Route("/transaction/delete/{id}", name="delete_transaction", methods={"DELETE"})
//     * @throws ApiException
//     */
//    public function deleteTransactionAction(string $id)
//    {
//        $this->transactionFacade->deleteTransaction((int)$id);
//
//        return new ApiResponse(
//            'Transaction has been removed',
//            Response::HTTP_OK
//        );
//    }
//
//    /**
//     * @Route("/transaction/update", name="update_transaction", methods={"PUT"})
//     * @ParamConverter("message", class="App\Message\UpdateTransactionMessage", converter="message_converter")
//     * @throws ApiException
//     */
//    public function updateTransactionAction(UpdateTransactionMessage $message)
//    {
//        $this->transactionFacade->updateTransaction($message);
//
//        return new ApiResponse(
//            'Transaction has been successfully updated',
//            Response::HTTP_CREATED
//        );
//    }
}