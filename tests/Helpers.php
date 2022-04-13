<?php

namespace App\Tests;

use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class Helpers extends WebTestCase
{
    public function jsonClient(
        string $httpMethod,
        string $httpUri,
        ?string $expectedJson = null,
        array $options = [],
        bool $isSuccessful = true,
        bool $isLoged = true,
        string $testUser = 'jake@jake.jake',
    ): void {
        $client = static::createClient();

        if ($isLoged) {
            $userRepository = static::getContainer()->get(UsersRepository::class);

            // retrieve the test user
            $authUser = $userRepository->findOneByEmail($testUser);

            // simulate $testUser being logged in
            $client->loginUser($authUser);
        }

        $client->xmlHttpRequest($httpMethod, $httpUri, $options);
        if ($isSuccessful){static::assertResponseIsSuccessful();}
        if ($expectedJson){static::assertJsonStringEqualsJsonString($expectedJson, $client->getResponse()->getContent());}
    }
}
