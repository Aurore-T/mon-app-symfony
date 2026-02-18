<?php

namespace App\Tests\Repository;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductRepositoryTest extends KernelTestCase
{
    public function testFindPaginate(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        $productRepository = $container->get(ProductRepository::class);
        $data = $productRepository->findPaginate();


        $this->assertEquals(10, count($data['products']));
    }
}
