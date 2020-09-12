<?php

namespace App\Controller;

use App\Http\ApiResponse;
use App\Message\CreateUserMessage;
use App\Service\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends AbstractController
{
    /**
     * @Route("/user", name="create_user", methods={"POST"})
     * @ParamConverter("message", class="App\Message\CreateUserMessage", converter="message_converter")
     */
    public function createAction(CreateUserMessage $message, UserManager $userManager)
    {
        $userManager->createUser($message);

        return new ApiResponse('User has been crated successfully.', Response::HTTP_CREATED);
    }
}
