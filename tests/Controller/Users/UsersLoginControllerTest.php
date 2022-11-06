<?php

namespace App\Tests\Controller\Users\Login;

use App\Tests\Helpers;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UsersLoginControllerTest extends WebTestCase
{
    public function testCreate()
    {
        $json = ['user' => [
            'email'    => $_ENV['EMAIL'],
            'password' => $_ENV['PASSWORD']
        ]];
        $h = new Helpers();
        $responseData = $h->jsonClient(method: 'POST', url: 'users/login', parameters: $json);

        $this->assertArrayHasKey('user',     $responseData);
        $this->assertArrayHasKey('email',    $responseData['user']);
        $this->assertArrayHasKey('username', $responseData['user']);
        $this->assertArrayHasKey('bio',      $responseData['user']);
        $this->assertArrayHasKey('image',    $responseData['user']);
        $this->assertArrayHasKey('token',    $responseData['user']);

        $file = '.env.test.local';
        $token = "TOKEN: {$responseData['user']['token']}";
        file_put_contents($file, $token);
    }
}