<?php

namespace App\Tests\Controller\Articles;

use App\Repository\ArticlesRepository;
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
        static::assertResponseIsSuccessful();
    }

    public function testCreateExpectUniqueConstraintViolationException()
    {
        $h = new Helpers();
        $httpParams = [
            'title' => 'How to train your dragon',
            'description' => 'Ever wonder how?',
            'body' => 'You have to believe',
        ];
        $h->jsonClient('POST', '/api/articles', null, $httpParams, false);
        static::assertResponseStatusCodeSame(422);
    }

    public function testDeleteUnauthenticated(): void
    {
        $client = static::createClient();
        $client->request('DELETE', '/api/articles/how-to-train-your-dragon');
        static::assertResponseStatusCodeSame(401);
    }

    public function testDelete()
    {
        $h = new Helpers();
        $h->jsonClient('DELETE', '/api/articles/how-to-train-your-dragon');
        static::assertResponseIsSuccessful();
    }

    public function testDeleteNotExistent()
    {
        $h = new Helpers();
        $h->jsonClient('DELETE', '/api/articles/not-existent-file', null, [], false);
        static::assertResponseStatusCodeSame(422);
    }

    public function testShow()
    {
        // php bin/console test:load-articles
        $h = new Helpers();
        $h->jsonClient(
            'GET',
            '/api/articles/how-to-train-your-dragonsss',
            '{  "article": { "slug": "how-to-train-your-dragonsss",    "title": "How to train your dragon",
                "description": "Ever wonder how?",    "body": "It takes a Jacobian", "tagList": ["dragons", "training"],
                    "createdAt": "2016-02-18T03:22:56.637Z",    "updatedAt": "2016-02-18T03:48:35.824Z",
                        "favorited": false,    "favoritesCount": 0,    "author": {      "username": "jake",      
                        "bio": "I work at statefarm",      "image": "https://i.stack.imgur.com/xHWG8.jpg",      
                        "following": false    }  }}',
            [],
            false
        );
        static::assertResponseIsSuccessful();
    }

    public function testShowNotFound()
    {
        $h = new Helpers();
        $h->jsonClient(
            'GET',
            '/api/articles/not-existent',
            null,
            [],
            false
        );
        static::assertResponseStatusCodeSame(422);
    }

    public function testShowNotFound()
    {
        $h = new Helpers();
        $h->jsonClient(
            'GET',
            '/api/articles/not-existent',
            null,
            [],
            false,
            false
        );
        static::assertResponseStatusCodeSame(422);
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
        static::assertResponseIsSuccessful();
    }

    public function testUpdateUnauthenticated(): void
    {
        $client = static::createClient();
        $client->request('PUT', '/api/articles/how-to-train-your-dragon');
        static::assertResponseStatusCodeSame(401);
    }

    public function testUpdate()
    {
        $h = new Helpers();
        $h->jsonClient('PUT', '/api/articles/how-to-train-your-dragon', '{
  "article": {
    "title": "Did you train your dragon?"
  }
}');
        static::assertResponseIsSuccessful();
    }

    public function testIndex(): void
    {
        $h = new Helpers();
        $h->jsonClient('GET', '/api/articles', '{  "articles":[{    
        "slug": "how-to-train-your-dragonsss", "title": "How to train your dragon", "description": "Ever wonder how?",
            "body": "It takes a Jacobian", "tagList": ["dragons", "training"], "createdAt": "2016-02-18T03:22:56.637Z",
                "updatedAt": "2016-02-18T03:48:35.824Z",    "favorited": false,    "favoritesCount": 0,    
                "author": {      "username": "jake",      "bio": "I work at statefarm",      
                "image": "https://i.stack.imgur.com/xHWG8.jpg",      "following": false    }  },
        {    "slug": "how-to-train-your-dragon",    "title": "Did you train your dragon?",    
        "description": "Ever wonder how?",    "body": "You have to believe",    "tagList": [],    
        "createdAt": "2016-02-18T03:22:56.637Z",    "updatedAt": "2016-02-18T03:48:35.824Z",    "favorited": false,    
        "favoritesCount": 0,    "author": {      "username": "jake",      "bio": "I work at statefarm",      
        "image": "https://i.stack.imgur.com/xHWG8.jpg",      "following": false    }  }],  "articlesCount": 2}');
//        $this->expectException('PHPUnit\Framework\ExpectationFailedException');
        static::assertResponseIsSuccessful();
    }
}
