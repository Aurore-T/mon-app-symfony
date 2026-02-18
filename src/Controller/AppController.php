<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AppController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function home(ProductRepository $productRepository): Response
    {
        return $this->render('home.html.twig', [
            'products' => $productRepository->findAll()
        ]);
    }

    #[Route('/admin/dashboard', name: 'app_admin_dashboard', methods: ['GET'])]
    public function admin(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    #[Route('/product/{id}', name: 'app_public_product_show', methods: ['GET'])]
    public function showProduct(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }
}

