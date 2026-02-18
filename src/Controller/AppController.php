<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AppController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function home(ProductRepository $productRepository): Response
    {
        $data = $productRepository->findPaginate(8);

        return $this->render('home.html.twig', [
            'products' => $data['products']
        ]);
    }

    #[Route('/catalog', name: 'app_product_catalog', methods: ['GET'])]
    public function catalogProduct(Request $request, ProductRepository $productRepository): Response
    {
        $size = isset($request->query->all()['size']) ? (int)$request->query->get('size') : 10;
        $page = isset($request->query->all()['page']) ? (int)$request->query->get('page') : 1;

        $data = $productRepository->findPaginate($size, $page);

        return $this->render('product/catalog.html.twig', [
            'products' => $data['products'],
            'count' => $data['count'],
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

