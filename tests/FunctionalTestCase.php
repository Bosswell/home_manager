<?php

namespace Tests;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class FunctionalTestCase extends WebTestCase
{
    protected KernelBrowser $client;
    protected EntityManagerInterface $entityManager;
    protected User $testUser;

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

        /** @noinspection all */
        $this->testUser = $this
            ->entityManager
            ->getRepository(User::class)
            ->findOneBy(['email' => 'jakub@home.pl']);
    }
}