<?php

namespace App\Service;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    private $produitRepo;
    private $session;

    public function __construct(ProduitRepository $produitRepository, RequestStack $requestStack){
        $this->produitRepo = $produitRepository;
        $this->session = $requestStack->getSession();
    }


    public function getCart(){
        $cart = $this->session->get('sessionCart',  []);
        $entityCart = [];

        foreach ($cart as $produitId=>$quantity)
        {
            $item = [
                'product'=>$this->produitRepo->find($produitId),
                'quantity'=>$quantity
            ];

            $entityCart[]=$item;
        }

        return $entityCart;
    }

    public function addProduit(Produit $produit, $quantity){
        $cart = $this->session->get('sessionCart', []);

        if (isset($cart[$produit->getId()])){
            $cart[$produit->getId()] = $cart[$produit->getId()]+$quantity;
        }else{
            $cart[$produit->getId()]=$quantity;
        }
        $this->session->set('sessionCart', $cart);
    }
    public function getTotal(){
        $total = 0;

        foreach ($this->getCart() as $item){
            $total += $item['product']->getPrice() * $item['quantity'];
        }
        return $total;
    }

    public function removeProduit(Produit $product){
        $cart = $this->session->get('sessionCart', []);
        $productId = $product->getId();

        if(isset($cart[$productId])){
            $cart[$productId]--;
            if ($cart[$productId]===0){
                unset($cart[$productId]);
            }
        }
        $this->session->set('sessionCart', $cart);
    }

    public function emptyCart(){
        $this->session->remove('sessionCart');
    }
    public function count(){
        $count = 0;
        $cart = $this->session->get('sessionCart', []);

        foreach ($cart as $quantity){
            $count+=$quantity;
        }
        return $count;
    }
    public function removeRow(Produit $product){
        $cart = $this->session->get('sessionCart', []);
        $productId = $product->getId();

        if (isset($cart[$productId])){
            unset($cart[$productId]);
        }
        $this->session->set('sessionCart', $cart);
    }
}