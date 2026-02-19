<?php

namespace App\Tests\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\InMemoryUser;

class CategoryControllerTest extends WebTestCase
{
    private static ?int $id = null;

    public function testIndexCategory(): void
    {
        $client = self::createClient();
        $user = new InMemoryUser('admin', 'password', ['ROLE_ADMIN']);
        $client->loginUser($user);

        $client->request('GET', '/admin/category');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    public function testNewCategory(): void
    {
        $client = self::createClient();
        $admin = new InMemoryUser('admin', 'password', ['ROLE_ADMIN']);
        $client->loginUser($admin);

        $crawler = $client->request('GET', '/admin/category/new');

        $buttonCrawlerNode = $crawler->selectButton('Save');

        $form = $buttonCrawlerNode->form();
        $form['category[title]'] = 'Titre category test';

        $client->submit($form);

        $container = self::getContainer();
        $category = $container->get(CategoryRepository::class)->findOneBy(['title' => 'Titre category test']);
        self::$id = $category->getId();

        $this->assertResponseRedirects('/admin/category');
    }

    public function testEditCategory(): void
    {
        $client = static::createClient();
        $admin = new InMemoryUser('admin', 'password', ['ROLE_ADMIN']);
        $client->loginUser($admin);

        $crawler = $client->request('GET', '/admin/category/' . self::$id . '/edit');
        $buttonCrawlerNode = $crawler->selectButton('Update');

        $form = $buttonCrawlerNode->form();
        $form['category[title]'] = 'Change category title';

        $client->submit($form);

        $this->assertResponseRedirects('/admin/category');

    }
    public function testShowCategory(): void
    {
        $client = self::createClient();

        $user = new InMemoryUser('admin', 'password', ['ROLE_ADMIN']);
        $client->loginUser($user);

        $client->request('GET', '/admin/category/' . self::$id);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
