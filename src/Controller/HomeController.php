<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;

class HomeController extends AbstractController
{
    /**
     * @Route("", name="home")
     */
    public function index(ProductRepository $productRepository)
    {
        $products = $productRepository->findBy([], [], 6);
        return $this->render('home/index.html.twig', [
            'productList' => $products,
        ]);
    }
}
