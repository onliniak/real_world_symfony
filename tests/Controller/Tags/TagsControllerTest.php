<?php

namespace App\Tests\Controller\Tags;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TagsControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', "{$_ENV['APIURL']}/tags");

        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        $this->assertIsArray($responseData);
        $this->assertArrayHasKey('tags', $responseData);
    }
}