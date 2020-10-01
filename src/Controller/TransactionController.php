<?php

namespace App\Controller;

use App\ApiException;
use App\Entity\TransactionType;
use App\Entity\User;
use App\Factory\PagerfantaFactory;
use App\Helper\TransactionSummaryCalculator;
use App\Http\ApiResponse;
use App\Message\Transaction\CreateTransactionMessage;
use App\Message\Transaction\GetTransactionSummaryMessage;
use App\Message\Transaction\ListTransactionsMessage;
use App\Message\Transaction\UpdateTransactionMessage;
use App\Repository\TransactionRepository;
use App\Service\ObjectValidator;
use App\Service\TransactionService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Tests\Controller\TransactionControllerTest;


/**
 * @see TransactionControllerTest
 */
class TransactionController extends AbstractController
{
    private TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * @Route("/transaction", name="create_transaction", methods={"POST"})
     * @ParamConverter("message", class=CreateTransactionMessage::class, converter="message_converter")
     * @throws ApiException
     */
    public function createTransactionAction(CreateTransactionMessage $message)
    {
        $this->transactionService->createTransaction($message);

        return new ApiResponse('Transaction has been successfully created', Response::HTTP_CREATED);
    }

    /**
     * @Route("/transaction/delete/{id}", name="delete_transaction", methods={"DELETE"})
     * @throws ApiException
     */
    public function deleteTransactionAction(string $id)
    {
        $this->transactionService->deleteTransaction((int)$id);

        return new ApiResponse(
            'Transaction has been removed',
            Response::HTTP_OK
        );
    }

    /**
     * @Route("/transaction/update", name="update_transaction", methods={"PUT"})
     * @ParamConverter("message", class=UpdateTransactionMessage::class, converter="message_converter")
     * @throws ApiException
     */
    public function updateTransactionAction(UpdateTransactionMessage $message)
    {
        $this->transactionService->updateTransaction($message);

        return new ApiResponse(
            'Transaction has been successfully updated',
            Response::HTTP_CREATED
        );
    }

    /**
     * @Route("/transaction/summary", name="get_transaction_summary", methods={"GET"})
     * @ParamConverter("message", class=GetTransactionSummaryMessage::class, converter="query_message_converter")
     */
    public function getTransactionSummary(TransactionRepository $repository, GetTransactionSummaryMessage $message)
    {
        /** @var User $user */
        $user = $this->getUser();
        $data['entries'] = $repository->findAllTransactionSummary(
            $user->getId(),
            $message->getStartDate(),
            $message->getEndDate()
        );

        $calculator = new TransactionSummaryCalculator($data['entries'] ?? []);
        $data = array_merge($data, $calculator->getSummaryInfo());

        return new ApiResponse(
            (bool)$data['entries'] ? 'Found entries' : 'No results',
            Response::HTTP_OK,
            $data
        );
    }

    /**
     * @Route("/transaction/types/list", name="list_transaction_types", methods={"GET"})
     */
    public function listTransactionTypesAction()
    {
        $transactionTypes = $this
            ->getDoctrine()
            ->getRepository(TransactionType::class)
            ->findAll()
        ;

        return new ApiResponse('', Response::HTTP_OK, $transactionTypes);
    }

    /**
     * @Route("/transaction/list", name="list_transactions", methods={"GET"})
     * @ParamConverter("message", class=ListTransactionsMessage::class, converter="query_message_converter")
     * @throws ApiException
     */
    public function listTransactionsAction(ListTransactionsMessage $message, TransactionRepository $repository, ObjectValidator $validator)
    {
        $validator->validate($message);

        /** @var User $user */
        $user = $this->getUser();

        $filterBy = $message->getFilterBy();
        $sortBy = $message->getSortBy();
        $qb = $repository->getTransactionListQuery(
            $user->getId(),
            $filterBy->getTransactionTypeId(),
            $filterBy->getLastDays(),
            $filterBy->isIncome(),
            $sortBy->getName(),
            $sortBy->getDirection()
        );
        $pagerfanta = PagerfantaFactory::build($qb, 't');

        $nbPages = $pagerfanta->getNbPages();
        $pagerfanta->setCurrentPage($message->getNbPage() > $nbPages ? $nbPages : $message->getNbPage());

        return new ApiResponse('Found entries', Response::HTTP_OK, [
            'nbPages' => $nbPages,
            'currentPage' => $pagerfanta->getCurrentPage(),
            'results' => $pagerfanta->getCurrentPageResults()
        ]);
    }
}
