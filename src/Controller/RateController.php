<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Rate;
use App\Repository\RateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RateController extends AbstractController
{
    #[Route('/rate/{id}/mark/{mark}', name: 'app_rate')]
    public function index(Produit $product, RateRepository $rateRepository, $mark = null, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser()->getProfile();

        if (!$user || !$product){
            $this->addFlash('danger', 'An error has occured');
            return $this->redirectToRoute('app_produit');
        }

        if (!ctype_digit($mark)){
            $this->addFlash('danger', 'An error has occured');
            return $this->redirectToRoute('app_produit');
        }
        if ($mark<0 || $mark >5){
            $this->addFlash('danger', 'An error has occured');
            return $this->redirectToRoute('app_produit');
        }

        $rate = $rateRepository->findOneBy([
            'author'=>$user,
            'product'=>$product
        ]);

        if (!$rate){
            $rate = new Rate();
            $rate->setAuthor($user);
            $rate->setProduct($product);
        }
        $rate->setMark($mark);
        $manager->persist($rate);
        $manager->flush();
        return $this->redirectToRoute('produit_show', ['id'=>$product->getId()]);
    }
}
