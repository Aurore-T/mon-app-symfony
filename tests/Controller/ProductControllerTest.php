<?php

namespace App\Tests\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\InMemoryUser;

class ProductControllerTest extends WebTestCase
{
    private static ?int $id = null;

    public function testIndex(): void
    {
        $client = self::createClient();
        $user = new InMemoryUser('admin', 'password', ['ROLE_ADMIN']);
        $client->loginUser($user);

        $client->request('GET', '/admin/product');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testNewProduct(): void
    {
        $client = static::createClient();
        $admin = new InMemoryUser('admin', 'password', ['ROLE_ADMIN']);
        $client->loginUser($admin);

        $crawler = $client->request('GET', '/admin/product/new');

        $buttonCrawlerNode = $crawler->selectButton('Save');

        $form = $buttonCrawlerNode->form();
        $form['product[title]'] = 'Titre test';
        $form['product[description]'] = 'Voici ma description';
        $form['product[price]'] = 250;
        $form['product[category]']->select('Chaussure');

        $client->submit($form);

        $container = self::getContainer();
        $product = $container->get(ProductRepository::class)->findOneBy(['title' => 'Titre test']);
        self::$id = $product->getId();

        $this->assertResponseRedirects('/admin/product');
    }

    public function testEditProduct(): void
    {
        $client = static::createClient();
        $admin = new InMemoryUser('admin', 'password', ['ROLE_ADMIN']);
        $client->loginUser($admin);

        $crawler = $client->request('GET', '/admin/product/' . self::$id . '/edit');
        $buttonCrawlerNode = $crawler->selectButton('Update');

        $form = $buttonCrawlerNode->form();
        $form['product[title]'] = 'Change product title';
        $form['product[description]'] = 'Voici ma description';
        $form['product[price]'] = 100;
        $form['product[category]']->select('Chaussure');

        $client->submit($form);

        $this->assertResponseRedirects('/admin/product');

    }
}
