<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\User\InMemoryUser;

class ProductControllerTest extends WebTestCase
{
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

        $this->assertResponseRedirects('/admin/product');
    }
}
