<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    #[Route('/', name: 'app_produit')]
    public function index(ProduitRepository $repository): Response
    {
        return $this->render('produit/index.html.twig', [
            'produits' => $repository->findAll(),
        ]);
    }

    #[Route('/create', name: 'produit_create')]
    public function create(EntityManagerInterface $manager){
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);



        return $this->renderForm('produit/create.html.twig', [
            'form'=>$form
        ]);
    }

    #[Route('/show/{id}', name: 'produit_show')]
    public function show(Produit $produit){
        return $this->render('produit/show.html.twig', [
            'produit'=>$produit
        ]);
    }
}
