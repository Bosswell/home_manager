<?php

namespace App\Controller;

use App\ApiException;
use App\Entity\User;
use App\Factory\PagerfantaFactory;
use App\Http\ApiResponse;
use App\Message\ExamHistory\ListExamHistoryMessage;
use App\Repository\ExamHistoryRepository;
use App\Service\ObjectValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


class ExamHistoryController extends AbstractController
{
    /**
     * @Route("/exam/history/list", name="get_history_list", methods={"GET"})
     * @ParamConverter("message", class=ListExamHistoryMessage::class, converter="query_message_converter")
     * @throws ApiException
     */
    public function listTransactionsAction(ListExamHistoryMessage $message, ExamHistoryRepository $repository, ObjectValidator $validator)
    {
        $validator->validate($message);

        /** @var User $user */
        $user = $this->getUser();

        $filterBy = $message->getFilterBy();
        $sortBy = $message->getSortBy();
        $qb = $repository->getExamHistory(
            $user->getId(),
            $filterBy->getUsername(),
            $filterBy->getUserNumber(),
            $filterBy->isActive(),
            $filterBy->getStartDate(),
            $sortBy->getName(),
            $sortBy->getDirection()
        );
        $pagerfanta = PagerfantaFactory::build($qb, 'eh');
        $pagerfanta->setMaxPerPage(25);

        $nbPages = $pagerfanta->getNbPages();
        $pagerfanta->setCurrentPage($message->getNbPage() > $nbPages ? $nbPages : $message->getNbPage());

        return new ApiResponse('Found entries', Response::HTTP_OK, [
            'nbPages' => $nbPages,
            'currentPage' => $pagerfanta->getCurrentPage(),
            'results' => $pagerfanta->getCurrentPageResults()
        ]);
    }
}