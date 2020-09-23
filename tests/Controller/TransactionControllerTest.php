<?php

namespace Tests\Controller;

use App\Entity\Transaction;
use App\Entity\TransactionType;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\HttpFoundation\Response;
use Tests\FunctionalTestCase;


class TransactionControllerTest extends FunctionalTestCase
{
    public function testListTransactionTypesAction(): void
    {
        $this->client->request('GET', '/transaction/types/list');

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertEmpty($content['errors']);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertArrayHasKey(0, $content['data']);
        $this->assertArrayHasKey('label', $content['data'][0]);
        $this->assertArrayHasKey('value', $content['data'][0]);
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }

    public function testGetTransactionSummary(): void
    {
        $this->client->request('GET', '/transaction/summary');

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertEmpty($content['errors']);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertArrayHasKey('entries', $data = $content['data']);

        $this->assertArrayHasKey('totalOutcome', $data);
        $this->assertArrayHasKey('totalSummary', $data);
        $this->assertArrayHasKey('totalIncome', $data);
        $this->assertArrayHasKey('totalDeductibleExpanses', $data);

        $this->assertArrayHasKey(0, $entries = $data['entries']);
        $this->assertArrayHasKey('transactionTypeId', $entries[0]);
        $this->assertArrayHasKey('name', $entries[0]);
        $this->assertArrayHasKey('totalAmount', $entries[0]);
        $this->assertArrayHasKey('nbEntries', $entries[0]);
        $this->assertArrayHasKey('incomeAmount', $entries[0]);
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }

    public function testListTransactionsAction(): void
    {
        $this->client->request('GET', '/transaction/list');

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertEmpty($content['errors']);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertArrayHasKey('nbPages', $content['data']);
        $this->assertArrayHasKey('currentPage', $content['data']);
        $this->assertArrayHasKey('results', $content['data']);
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }

    /**
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function testCreateTransactionAction(): void
    {
        $qb = $this->entityManager->createQueryBuilder();
        $transactionTypeId = $qb->select('tt.id')
            ->from(TransactionType::class, 'tt')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult();

        $this->client->request('POST', '/transaction', [], [], [], json_encode([
            'transactionTypeId' => (int)$transactionTypeId,
            'amount' => rand(20, 120),
            'description' => 'Hello world'
        ]));

        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }

    public function testDeleteTransaction()
    {
        $transRepository = $this->entityManager->getRepository(Transaction::class);
        /** @var Transaction $transaction */
        $transaction = $transRepository->findOneBy(['isDeleted' => false, 'user' => $this->testUser]);

        $this->client->request('DELETE', '/transaction/delete/' . $transaction->getId());
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->entityManager->clear();
        $transactionAfter = $transRepository->find($transaction->getId());
        $this->assertEquals(true, $transactionAfter->isDeleted());
    }

    public function testUpdateTransaction()
    {
        $transRepository = $this->entityManager->getRepository(Transaction::class);
        /** @var Transaction $transaction */
        $transaction = $transRepository->findOneBy(['isDeleted' => false, 'user' => $this->testUser]);

        $this->client->request('PUT', '/transaction/update', [], [], [], json_encode([
            'id' => $transaction->getId(),
            'transactionTypeId' => $transaction->getTransactionType()->getId(),
            'amount' => 100,
            'description' => 'Hello world',
            'taxPercentage' => 23
        ]));

        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->entityManager->clear();

        /** @var Transaction $transactionAfter */
        $transactionAfter = $transRepository->find($transaction->getId());
        $this->assertEquals(100, $transactionAfter->getAmount());
        $this->assertEquals('Hello world', $transactionAfter->getDescription());
        $this->assertEquals(23, $transactionAfter->getTaxPercentage());
    }
}