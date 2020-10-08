<?php

namespace App\Controller;

use App\Http\ApiResponse;
use App\Message\CreateUserMessage;
use App\Repository\UserRepository;
use App\Service\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends AbstractController
{
    /**
     * @Route("/user", name="create_user", methods={"POST"})
     * @ParamConverter("message", class=CreateUserMessage::class, converter="message_converter")
     */
    public function createAction(CreateUserMessage $message, UserManager $userManager)
    {
        $userManager->createUser($message);

        return new ApiResponse('User has been crated successfully.', Response::HTTP_CREATED);
    }

    /**
     * @Route("/user/list/questions", name="list_user_questions", methods={"GET"})
     */
    public function getQuestionsAction(Request $request, UserRepository $repository)
    {
        $searchBy = $request->get('searchBy');

        if (is_null($searchBy) || strlen($searchBy) < 3) {
            $data = [];
        } else {
            $data = $repository->getQuestions($this->getUser()->getId(), $searchBy);
        }

        return new ApiResponse('User has been crated successfully.', Response::HTTP_CREATED, $data);
    }
}
