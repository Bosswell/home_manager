<?php

namespace App\Controller;

use App\ApiException;
use App\Entity\Exam;
use App\Entity\User;
use App\Exam\ExamFacade;
use App\Factory\PagerfantaFactory;
use App\Http\ApiResponse;
use App\Message\Exam\CreateExamMessage;
use App\Message\Exam\ListExamsMessage;
use App\Message\Exam\StartExamMessage;
use App\Message\Exam\UpdateExamMessage;
use App\Message\Exam\ValidateExamMessage;
use App\Repository\ExamRepository;
use App\Service\ExamManager;
use App\Service\ObjectValidator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;


class ExamController extends AbstractController
{
    private ExamManager $examService;
    private ExamFacade $examFacade;

    public function __construct(ExamManager $examService, ExamFacade $examFacade)
    {
        $this->examService = $examService;
        $this->examFacade = $examFacade;
    }

    /**
     * @Route("/exam/{id}", name="get_exam", methods={"GET"})
     * @throws ApiException
     */
    public function getExamAction(string $id, ExamRepository $repository, Serializer $serializer)
    {
        $exam = $repository->findOneBy(['id' => $id, 'user' => $this->getUser(), 'isDeleted' => false]);

        if (is_null($exam)) {
            throw ApiException::entityNotFound($id, Exam::class);
        }

        try {
            $data = $serializer->normalize($exam, null, ['groups' => 'details']);
        } catch (\Throwable $ex) {
            throw new ApiException($ex->getMessage(), $ex->getCode());
        }

        return new ApiResponse('Exam has been found', Response::HTTP_OK, $data);
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
     * @Route("/exam/list/exams", name="list_exams", methods={"GET"})
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
     * @Route("/exam/front/start", name="start_exam", methods={"POST"})
     * @ParamConverter("message", class=StartExamMessage::class, converter="message_converter")
     * @throws ApiException
     */
    public function startExamAction(StartExamMessage $message)
    {
        $history = $this->examFacade->startExam($message);

        return new ApiResponse(
            'Exam has been started',
            Response::HTTP_CREATED,
            [
                'historyId' => $history->getId(),
                'exam' => $history->getNormalizedExam(),
                'mode' => $history->getMode()
            ]
        );
    }

    /**
     * @Route("/exam/front/validate", name="validate_exam", methods={"POST"})
     * @ParamConverter("message", class=ValidateExamMessage::class, converter="message_converter")
     * @throws ApiException
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

    /**
     * @Route("/exam/check/validity/{id}", name="check_exam_validity", methods={"GET"})
     * @throws ApiException
     */
    public function checkExamValidityAction(string $id)
    {
        $this->examFacade->checkExamValidity((int)$id);

        return new ApiResponse(
            'Exam is valid',
            Response::HTTP_OK
        );
    }
}
