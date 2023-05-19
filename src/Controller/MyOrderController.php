<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MyOrderController extends AbstractController
{
    #[Route('/my/order', name: 'app_my_order')]
    public function index(OrderRepository $repository): Response
    {
        return $this->render('my_order/index.html.twig', [
            'orders' => $repository->findBy([
                'profile'=>$this->getUser()->getProfile()
            ]),
        ]);
    }


    #[Route('/my/order/{symfony id}', name: 'app_myorder_show', methods: 'GET')]
    public function show(Order $order){
        if ($this->getUser()->getProfile() !==$order->getProfile()){
            $this->addFlash('warning', '
            mind your own business');
            return $this->redirectToRoute('app_produit');
        }
        return $this->render('my_order/show.html.twig',[
            'order'=>$order
        ]);
    }
}
