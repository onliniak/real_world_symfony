<?php

namespace App\Tests;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class Helpers extends WebTestCase
{
    public function jsonClient(string $method, string $url, ?array $parameters = []): array
    {
        $client = static::createClient();
        $client->request(
          $method,
          "{$_ENV['APIURL']}/{$url}",
          [],
          [],
          ['CONTENT_TYPE' => 'application/json'],
          json_encode($parameters)
        );

        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        $this->assertIsArray($responseData);

        return $responseData;
    }
}