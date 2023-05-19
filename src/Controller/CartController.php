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
    public function index(CartService $cartService): Response
    {
        return $this->render('cart/index.html.twig', [
            'cart'=>$cartService->getCart(),
            'total'=>$cartService->getTotal()
        ]);


    }

    #[Route('/cart/add/{id}/{quantity}', name: 'app_cart_add')]
    #[Route('/cart/addfromcart/{id}/{quantity}', name: 'app_cart_add_from_cart')]
    public function add(Request $request, Produit $produit, $quantity, CartService $cartService){
        $cartService->addProduit($produit, $quantity);
        $route = $request->attributes->get('_route');

        $redirection = 'app_produit';

        if ($route ==="app_cart_add_from_cart"){
            $redirection = 'app_cart';
        }

        return $this->redirectToRoute($redirection);
    }


    #[Route('/cart/removeOne/{id}', name:'app_cart_removeone')]
    public function removeOne(CartService $cartService, Produit $produit){
        $cartService->removeProduit($produit);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/removeWhole/{id}', name: 'app_cart_removewhole')]
    public function removeWhole(CartService $cartService, Produit $product){
        $cartService->removeRow($product);
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/emptyCart', name: 'app_cart_emptycart')]
    public function emptyCart(CartService $cartService): Response{
        $cartService->emptyCart();

        return $this->redirectToRoute('app_cart');
    }
}
