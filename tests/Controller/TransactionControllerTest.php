<?php

namespace Tests\Controller;

use App\Entity\Transaction;
use App\Entity\TransactionType;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


class TransactionControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient([], ['CONTENT_TYPE' => 'application/json']);
        $this->client->request('POST', '/login_check', [] , [], [], json_encode([
            'username' => 'jakub@home.pl',
            'password' => 'zaq1@WSX'
        ]));

        $content = json_decode($this->client->getResponse()->getContent(), true);

        $kernel = self::bootKernel();

        $this->client->setServerParameter('HTTP_AUTHORIZATION', 'Bearer ' . $content['token']);
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

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
        $this->assertArrayHasKey(0, $content['data']);
        $this->assertArrayHasKey('transactionTypeId', $content['data'][0]);
        $this->assertArrayHasKey('name', $content['data'][0]);
        $this->assertArrayHasKey('amount', $content['data'][0]);
        $this->assertArrayHasKey('entries', $content['data'][0]);
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
        $testUser = $this
            ->entityManager
            ->getRepository(User::class)
            ->findOneBy(['email' => 'jakub@home.pl']);

        $transRepository = $this->entityManager->getRepository(Transaction::class);
        /** @var Transaction $transaction */
        $transaction = $transRepository->findOneBy(['isDeleted' => false, 'user' => $testUser]);

        $this->client->request('DELETE', '/transaction/delete/' . $transaction->getId());
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->entityManager->clear();
        $transactionAfter = $transRepository->find($transaction->getId());
        $this->assertEquals(true, $transactionAfter->isDeleted());
    }
}