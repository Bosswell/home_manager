<?php

namespace App\Controller;

use App\ApiException;
use App\Entity\Option;
use App\Entity\Question;
use App\Http\ApiResponse;
use App\Message\Option\CreateOptionMessage;
use App\Message\Option\UpdateOptionMessage;
use App\Repository\OptionRepository;
use App\Service\ObjectValidator;
use App\Service\QuestionManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class OptionController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private ObjectValidator $validator;
    private OptionRepository $optionRepository;
    private QuestionManager $questionManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        ObjectValidator $validator,
        OptionRepository $optionRepository,
        QuestionManager $questionManager
    ) {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->optionRepository = $optionRepository;
        $this->questionManager = $questionManager;
    }

    /**
     * @Route("/option/{id}", name="get_option", methods={"GET"})
     * @throws ApiException
     */
    public function getOptionAction(string $id)
    {
        $option = $this->optionRepository->find($id);

        if (is_null($option)) {
            throw ApiException::entityNotFound((int)$id, Option::class);
        }

        return new ApiResponse('Option has been successfully created', Response::HTTP_OK, [
            'id' => $option->getId(),
            'content' => $option->getContent(),
            'isCorrect' => $option->isCorrect()
        ]);
    }

    /**
     * @Route("/option", name="create_option", methods={"POST"})
     * @ParamConverter("message", class=CreateOptionMessage::class, converter="message_converter")
     * @throws ApiException
     */
    public function createOptionAction(CreateOptionMessage $message)
    {
        $option = new Option($message->getContent(), $message->isCorrect());
        $this->validator->validate($option);

        $this->entityManager->persist($option);
        $this->entityManager->flush();

        /** @var Question $question */
        $question = $this->entityManager->getRepository(Question::class)
            ->findOneBy(['id' => $message->getQuestionId(), 'user' => $this->getUser()]);

        if (is_null($question)) {
            throw ApiException::entityNotFound($message->getQuestionId(), Question::class);
        }

        $question->addOption($option);
        $this->entityManager->flush();

        return new ApiResponse('Option has been successfully created', Response::HTTP_CREATED, [
            'id' => $option->getId(),
            'content' => $option->getContent(),
            'isCorrect' => $option->isCorrect()
        ]);
    }

    /**
     * @Route("/option/delete/{id}", name="delete_option", methods={"DELETE"})
     * @throws ApiException
     */
    public function deleteOptionAction(string $id)
    {
        $option = $this->optionRepository
            ->findOneBy(['id' => $id]);

        if (is_null($option)) {
            throw ApiException::entityNotFound((int)$id, Option::class);
        }

        $this->denyAccessUnlessGranted('delete', $option);

        $this->entityManager->remove($option);
        $this->entityManager->flush();

        return new ApiResponse(
            'Option has been removed',
            Response::HTTP_OK
        );
    }

    /**
     * @Route("/option/update", name="update_option", methods={"PUT"})
     * @ParamConverter("message", class=UpdateOptionMessage::class, converter="message_converter")
     * @throws ApiException
     */
    public function updateOptionAction(UpdateOptionMessage $message)
    {
        $option = $this->optionRepository
            ->findOneBy(['id' => $message->getOptionId()]);

        if (is_null($option)) {
            throw ApiException::entityNotFound($message->getOptionId(), Option::class);
        }

        $this->denyAccessUnlessGranted('edit', $option);

        $option->update($message->getContent(), $message->isCorrect());
        $this->entityManager->flush();

        return new ApiResponse(
            'Option has been successfully updated',
            Response::HTTP_CREATED
        );
    }
}
