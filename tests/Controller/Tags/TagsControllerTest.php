<?php

namespace App\Tests\Controller\Tags;

use App\Tests\Helpers;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TagsControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $h = new Helpers();
        $responseData = $h->jsonClient(method: 'GET', url: 'tags');

        $this->assertIsArray($responseData);
        $this->assertArrayHasKey('tags', $responseData);
    }
}
