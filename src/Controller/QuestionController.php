<?php

namespace App\Controller;

use App\ApiException;
use App\Entity\Exam;
use App\Entity\Question;
use App\Http\ApiResponse;
use App\Message\Question\CreateQuestionMessage;
use App\Message\Question\LinkQuestionMessage;
use App\Message\Question\UpdateQuestionMessage;
use App\Repository\QuestionRepository;
use App\Service\ObjectValidator;
use App\Service\QuestionManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;


class QuestionController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private ObjectValidator $validator;
    private QuestionRepository $questionRepository;
    private QuestionManager $questionManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        ObjectValidator $validator,
        QuestionRepository $questionRepository,
        QuestionManager $questionManager
    ) {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->questionRepository = $questionRepository;
        $this->questionManager = $questionManager;
    }

    /**
     * @Route("/question/{id}", name="get_question", methods={"GET"})
     * @throws ApiException
     */
    public function getQuestionAction(string $id, Serializer $serializer)
    {
        $question = $this->questionRepository
            ->findOneBy(['id' => $id, 'user' => $this->getUser()]);

        if (is_null($question)) {
            throw ApiException::entityNotFound($id, Question::class);
        }

        try {
            $data = $serializer->normalize($question, null, ['groups' => 'question-details']);
        } catch (\Throwable $ex) {
            throw new ApiException($ex->getMessage(), $ex->getCode());
        }

        return new ApiResponse('Question has been found', Response::HTTP_OK, $data);
    }

    /**
     * @Route("/question", name="create_question", methods={"POST"})
     * @ParamConverter("message", class=CreateQuestionMessage::class, converter="message_converter")
     * @throws ApiException
     */
    public function createQuestionAction(CreateQuestionMessage $message)
    {
        $question = new Question($message->getQuery(), $this->getUser());
        $this->validator->validate($question);

        $this->entityManager->persist($question);
        $this->entityManager->flush();

        /** @var Exam $exam */
        $exam = $this->entityManager->getRepository(Exam::class)
            ->findOneBy(['id' => $message->getExamId(), 'user' => $this->getUser()]);

        if (is_null($exam)) {
            throw ApiException::entityNotFound($message->getExamId(), Exam::class);
        }

        $exam->addQuestion($question);
        $this->entityManager->flush();

        return new ApiResponse('Question has been successfully created', Response::HTTP_CREATED, [
            'id' => $question->getId(),
            'query' => $question->getQuery()
        ]);
    }

    /**
     * @Route("/question/delete/{id}", name="delete_question", methods={"DELETE"})
     * @throws ApiException
     */
    public function deleteQuestionAction(string $id)
    {
        $question = $this->questionRepository
            ->findOneBy(['id' => $id, 'user' => $this->getUser()]);

        if (is_null($question)) {
            throw ApiException::entityNotFound($id, Question::class);
        }

        $this->entityManager->remove($question);
        $this->entityManager->flush();

        return new ApiResponse(
            'Question has been removed',
            Response::HTTP_OK
        );
    }

    /**
     * @Route("/question/update", name="update_question", methods={"PUT"})
     * @ParamConverter("message", class=UpdateQuestionMessage::class, converter="message_converter")
     * @throws ApiException
     */
    public function updateQuestionAction(UpdateQuestionMessage $message)
    {
        $question = $this->questionRepository
            ->findOneBy(['id' => $message->getId(), 'user' => $this->getUser()]);

        if (is_null($question)) {
            throw ApiException::entityNotFound($message->getId(), Question::class);
        }

        $question->update($message->getQuery());
        $this->entityManager->flush();

        return new ApiResponse(
            'Question has been successfully updated',
            Response::HTTP_CREATED
        );
    }

    /**
     * @Route("/question/unlink", name="unlink_question", methods={"POST"})
     * @ParamConverter("message", class=LinkQuestionMessage::class, converter="message_converter")
     * @throws ApiException
     */
    public function unlinkQuestionAction(LinkQuestionMessage $message)
    {
        $this->questionManager->unlinkQuestionAction($message);

        return new ApiResponse(
            'Question has been successfully unlinked',
            Response::HTTP_CREATED
        );
    }

    /**
     * @Route("/question/link", name="link_question", methods={"POST"})
     * @ParamConverter("message", class=LinkQuestionMessage::class, converter="message_converter")
     * @throws ApiException
     */
    public function linkQuestionAction(LinkQuestionMessage $message)
    {
        $this->questionManager->linkQuestionAction($message);

        return new ApiResponse(
            'Question has been successfully linked',
            Response::HTTP_CREATED
        );
    }
}
