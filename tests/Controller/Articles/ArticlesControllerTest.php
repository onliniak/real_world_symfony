<?php

namespace App\Tests\Controller\Articles;

use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArticlesControllerTest extends WebTestCase
{
    public function testShow()
    {
    }

    public function testCreateUnauthenticated(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/articles');
        static::assertResponseStatusCodeSame(401);
    }

    public function testCreateWithTagList(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UsersRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('jake@jake.jake');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $client->xmlHttpRequest('POST', '/api/articles', [
            'title' => 'How to train your dragon',
            'description' => 'Ever wonder how?',
            'body' => 'You have to believe',
            'tagList' => [
                'reactjs',
                'angularjs',
                'dragons',
            ],
        ]);
        static::assertResponseIsSuccessful();
    }

    public function testCreateWithoutTagList(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UsersRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('jake@jake.jake');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $client->xmlHttpRequest('POST', '/api/articles', [
            'title' => 'How to train your dragon',
            'description' => 'Ever wonder how?',
            'body' => 'You have to believe'
        ]);
        static::assertResponseIsSuccessful();
    }

    public function testDelete()
    {
    }

    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/articles');
    }

    public function testUpdate()
    {
    }
}
