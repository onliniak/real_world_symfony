<?php

namespace App\Tests\Controller\Articles;

use App\Tests\Helpers;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArticlesControllerTest extends WebTestCase
{
    public function testCreateUnauthenticated(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/articles');
        static::assertResponseStatusCodeSame(401);
    }

    public function testCreateWithTagList(): void
    {
        $h = new Helpers();
        $httpParams = [
            'title' => 'How to train your dragon',
            'description' => 'Ever wonder how?',
            'body' => 'You have to believe',
            'tagList' => [
                'reactjs',
                'angularjs',
                'dragons',
            ],
        ];
        $h->jsonClient('POST', '/api/articles', '{
  "article": {
    "title": "How to train your dragon",
    "description": "Ever wonder how?",
    "body": "You have to believe",
    "tagList": ["reactjs", "angularjs", "dragons"]
  }
}', $httpParams);
    }

    public function testDelete()
    {
    }

    public function testShow()
    {
    }

    public function testCreateWithoutTagList(): void
    {
        $h = new Helpers();
        $h->jsonClient('POST', '/api/articles', '{
  "article": {
    "title": "How to train your dragon",
    "description": "Ever wonder how?",
    "body": "You have to believe"
  }
}');
    }

    public function testUpdate()
    {
    }

    public function testIndex(): void
    {
    }
}
