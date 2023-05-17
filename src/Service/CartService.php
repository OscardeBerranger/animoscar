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

    public function removeProduit(){}
}