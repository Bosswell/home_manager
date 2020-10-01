<?php

namespace Tests\Controller;

use App\Entity\Exam;
use Symfony\Component\HttpFoundation\Response;
use Tests\FunctionalTestCase;


class ExamControllerTest extends FunctionalTestCase
{
    public function testCreateExamAction(): void
    {
        $examRepository = $this->entityManager->getRepository(Exam::class);

        $this->client->request('POST', '/exam', [], [], [], json_encode([
            'name' => 'First exam',
            'code' => 'OKON'
        ]));
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        $exam = $examRepository->findBy([], ['id' => 'DESC'],1,0)[0];

        $this->assertEquals('First exam', $exam->getName());
        $this->assertEquals('OKON', $exam->getCode());
    }

    public function testUpdateExam()
    {
        $examRepository = $this->entityManager->getRepository(Exam::class);
        /** @var Exam $exam */
        $exam = $examRepository->findOneBy(['isDeleted' => false, 'user' => $this->testUser]);

        $this->client->request('PUT', '/exam/update', [], [], [], json_encode([
            'id' => $exam->getId(),
            'name' => 'Updated exam',
            'code' => 'HELLO',
        ]));
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->entityManager->clear();

        /** @var Exam $examAfter */
        $examAfter = $examRepository->find($exam->getId());
        $this->assertEquals('Updated exam', $examAfter->getName());
        $this->assertEquals('HELLO', $examAfter->getCode());
    }

    public function testDeleteExam()
    {
        $examRepository = $this->entityManager->getRepository(Exam::class);
        /** @var Exam $exam */
        $exam = $examRepository->findOneBy(['isDeleted' => false, 'user' => $this->testUser]);

        $this->client->request('DELETE', '/exam/delete/' . $exam->getId());
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->entityManager->clear();

        $examAfter = $examRepository->find($exam->getId());
        $this->assertEquals(true, $examAfter->isDeleted());
    }

    public function testListTExamsAction(): void
    {
        $this->client->request('GET', '/exam/action/list');

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertEmpty($content['errors']);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertArrayHasKey('nbPages', $content['data']);
        $this->assertArrayHasKey('currentPage', $content['data']);
        $this->assertArrayHasKey('results', $content['data']);
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }
}