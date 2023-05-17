<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(CartService $cartServices): Response
    {
        return $this->render('cart/index.html.twig', [
            'cart'=>$cartServices->getCart()
        ]);


    }

    #[Route('/cart/add/{id}/{quantity}', name: 'app_cart_add')]
    public function add(Request $request, Produit $produit, $quantity, CartService $cartServices){
        $cartServices->addProduit($produit, $quantity);
        return $this->redirectToRoute('app_produit');
    }


    #[Route('/cart/removeOne/{id}')]
    public function removeOne(CartService $cartService, Produit $produit){

    }
}
