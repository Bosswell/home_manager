<?php

namespace App\Controller;

use App\ApiController;
use App\ApiException;
use App\Entity\TransactionType;
use App\Entity\User;
use App\Http\ApiResponse;
use App\Message\CreateTransactionMessage;
use App\Message\ListTransactionsMessage;
use App\Repository\TransactionRepository;
use App\Service\ObjectValidator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Query\QueryBuilder;
use Pagerfanta\Doctrine\DBAL\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Tests\Controller\TransactionControllerTest;

/**
 * @see TransactionControllerTest
 */
class TransactionController extends ApiController
{
    /**
     * @Route("/transaction", name="create_transaction", methods={"POST"})
     * @ParamConverter("message", class="App\Message\CreateTransactionMessage", converter="message_converter")
     * @throws ApiException
     */
    public function createTransactionAction(CreateTransactionMessage $message)
    {
        $this->transactionFacade->createTransaction($message);

        return new ApiResponse('Transaction has been successfully created', Response::HTTP_CREATED);
    }

    /**
     * @Route("/transaction/summary", name="get_transaction_summary", methods={"GET"})
     */
    public function getTransactionSummary(TransactionRepository $repository)
    {
        $data = $repository->findAllTransactionSummary();

        return new ApiResponse(
            (bool)$data ? 'Found entries' : 'No results',
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
     * @Route("/transaction/list", name="list_transaction", methods={"GET"})
     * @ParamConverter("message", class="App\Message\ListTransactionsMessage", converter="query_message_converter")
     * @throws ApiException
     */
    public function listTransactionsAction(ListTransactionsMessage $message, TransactionRepository $repository, ObjectValidator $validator)
    {
        $validator->validate($message);

        /** @var User $user */
        $user = $this->getUser();
        $countQueryBuilderModifier = function (QueryBuilder $queryBuilder): void {
            $queryBuilder->select('COUNT(DISTINCT t.id) AS total_results')
                ->setMaxResults(1);
        };

        $filterBy = $message->getFilterBy();
        $sortBy = $message->getSortBy();
        $qb = $repository->getTransactionListQuery(
            $user->getId(),
            $filterBy->getTransactionTypeId(),
            $filterBy->getLastDays(),
            $sortBy->getName(),
            $sortBy->getDirection()
        );
        $adapter = new QueryAdapter($qb, $countQueryBuilderModifier);
        $pagerfanta = new Pagerfanta($adapter);

        $nbPages = $pagerfanta->getNbPages();

        $pagerfanta->setCurrentPage($message->getPage() > $nbPages ? $nbPages : $message->getPage() );
        
        return new ApiResponse('Found entries', Response::HTTP_OK, [
            'nbPages' => $nbPages,
            'currentPage' => $pagerfanta->getCurrentPage(),
            'results' => $pagerfanta->getCurrentPageResults()
        ]);
    }
}
