<?php

namespace App\Controller;

use App\ApiException;
use App\Entity\User;
use App\Exam\ExamFacade;
use App\Factory\PagerfantaFactory;
use App\Http\ApiResponse;
use App\Message\Exam\CreateExamMessage;
use App\Message\Exam\ListExamsMessage;
use App\Message\Exam\UpdateExamMessage;
use App\Message\Exam\ValidateExamMessage;
use App\Repository\ExamRepository;
use App\Service\ExamService;
use App\Service\ObjectValidator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ExamController extends AbstractController
{
    private ExamService $examService;
    private ExamFacade $examFacade;

    public function __construct(ExamService $examService, ExamFacade $examFacade)
    {
        $this->examService = $examService;
        $this->examFacade = $examFacade;
    }

    /**
     * @Route("/exam", name="create_exam", methods={"POST"})
     * @ParamConverter("message", class=CreateExamMessage::class, converter="message_converter")
     * @throws ApiException
     */
    public function createExamAction(CreateExamMessage $message)
    {
        $this->examService->createExam($message);

        return new ApiResponse('Exam has been successfully created', Response::HTTP_CREATED);
    }

    /**
     * @Route("/exam/delete/{id}", name="delete_exam", methods={"DELETE"})
     * @throws ApiException
     */
    public function deleteExamAction(string $id)
    {
        $this->examService->deleteExam((int)$id);

        return new ApiResponse(
            'Exam has been removed',
            Response::HTTP_OK
        );
    }

    /**
     * @Route("/exam/update", name="update_exam", methods={"PUT"})
     * @ParamConverter("message", class=UpdateExamMessage::class, converter="message_converter")
     * @throws ApiException
     */
    public function updateExamAction(UpdateExamMessage $message)
    {
        $this->examService->updateExam($message);

        return new ApiResponse(
            'Exam has been successfully updated',
            Response::HTTP_CREATED
        );
    }

    /**
     * @Route("/exam/action/list", name="list_exams", methods={"GET"})
     * @ParamConverter("message", class=ListExamsMessage::class, converter="query_message_converter")
     * @throws ApiException
     */
    public function listExamsAction(ListExamsMessage $message, ExamRepository $repository, ObjectValidator $validator)
    {
        $validator->validate($message);

        /** @var User $user */
        $user = $this->getUser();

        $sortBy = $message->getSortBy();
        $qb = $repository->getExamsListQuery(
            $user->getId(),
            $sortBy->getName(),
            $sortBy->getDirection()
        );

        $pagerfanta = PagerfantaFactory::build($qb, 'e');
        $nbPages = $pagerfanta->getNbPages();

        $pagerfanta->setCurrentPage($message->getNbPage() > $nbPages ? $nbPages : $message->getNbPage());

        return new ApiResponse('Found entries', Response::HTTP_OK, [
            'nbPages' => $nbPages,
            'currentPage' => $pagerfanta->getCurrentPage(),
            'results' => $pagerfanta->getCurrentPageResults()
        ]);
    }

    /**
     * @Route("/exam/action/validate", name="validate_exam", methods={"GET"})
     * @ParamConverter("message", class=ValidateExamMessage::class, converter="message_converter")
     */
    public function validateExamAction(ValidateExamMessage $message)
    {
        $result = $this->examFacade->validateExam($message);

        return new ApiResponse(
            'Exam has been validated',
            Response::HTTP_OK,
            $result->toArray()
        );
    }
}