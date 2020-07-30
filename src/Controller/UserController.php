<?php

namespace App\Controller;

use App\Http\ApiResponse;
use App\Message\CreateUserMessage;
use App\Service\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="create_user", methods={"POST"})
     * @ParamConverter("createTransaction", class="App\Message\CreateUserMessage", converter="message_converter")
     */
    public function createAction(CreateUserMessage $message, UserManager $userManager)
    {
        $userManager->createUser($message);

        return new ApiResponse('User has been crated successfully.', Response::HTTP_CREATED);
    }

    /**
     * @Route("/user", name="get_user", methods={"GET"})
     */
    public function getAction(Request $request)
    {
//        print_r(->);die();
//        $user = $this->getUser();

        return new ApiResponse('User has been crated successfully.', Response::HTTP_CREATED, [$user->getUsername()]);
    }
}
