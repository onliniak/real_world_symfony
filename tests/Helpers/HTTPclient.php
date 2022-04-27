<?php

namespace App\Tests\Helpers;

use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class HTTPclient extends WebTestCase
{
    private function initClient(): KernelBrowser
    {
        return static::createClient();
    }

    public function sendRequest(string $httpMethod, string $httpUrl, ?array $options = null): Crawler
    {
        return $this->initClient()
            ->xmlHttpRequest($httpMethod, $httpUrl, $options ?? []);
    }

    public function sendRequestAuthenticated(string $httpMethod, string $httpUrl, ?array $options = null,
                                             string $testUser = 'jake@jake.jake'): Crawler
    {
        $userRepository = static::getContainer()->get(UsersRepository::class);

        // retrieve the test user
        $authUser = $userRepository->findOneByEmail($testUser);

        return $this->initClient()
            ->loginUser($authUser)
            ->xmlHttpRequest($httpMethod, $httpUrl, $options ?? []);
    }
}
