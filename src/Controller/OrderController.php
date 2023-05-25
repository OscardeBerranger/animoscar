<?php

namespace App\Controller;

use App\Entity\Addresse;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/selectAddress', name: 'app_selecte_addresse')]
    public function index(): Response
    {
        return $this->render('order/address_selection.html.twig', [
        ]);
    }

    #[Route('/payment/{id}', name: 'app_order_payment')]
    public function payment(Addresse $addresse){
        return $this->render('order/payment.html.twig', [
            'address'=>$addresse
        ]);
    }


    #[Route('/makeorder/{id}', name: 'app_order_makeorder')]
    public function makeOrder(HomeController $controller, Addresse $addresse, CartService $cartService, EntityManagerInterface $manager): Response{
        if (!$this->getUser()){
            return $this->redirectToRoute('app_produit');
        }
        $order = new Order();

        $order->setProfile($this->getUser()->getProfile());
        $order->setAddress($addresse);
        $order->setStatus(2);
        $order->setTotal($cartService->getTotal());


        foreach ($cartService->getCart() as $item) {
            $orderItem = new OrderItem();
            $orderItem->setProduct($item['product']);
            $orderItem->setQuantity($item['quantity']);
            $orderItem->setOfOrder($order);

            $manager->persist($orderItem);
        }

        $manager->flush();
        $cartService->emptyCart();

        $this->addFlash('success', 'order confirmed');

        return $this->redirectToRoute('app_home_testmail', ['id'=>$order->getId()]);
//        return $this->redirectToRoute('app_myorder_show', ['id'=>$order->getId()]);
    }
}
