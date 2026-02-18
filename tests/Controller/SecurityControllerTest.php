<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\InMemoryUser;

class SecurityControllerTest extends WebTestCase
{
    public function testPageHome(): void
    {
        $client = self::createClient();
        $client->request('GET', '/');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testRedirectToLogin(): void
    {
        $client = self::createClient();
        $client->request('GET', '/admin/dashboard');

        $this->assertResponseRedirects('/login');
    }
        public function testVisitorUnauthorized(): void
    {
        $client = self::createClient();
        $user = new InMemoryUser('user', 'password', ['ROLE_USER']);
        $client->loginUser($user);

        $client->request('GET', '/admin/dashboard');

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
}
