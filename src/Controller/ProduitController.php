<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/admin/create', name: 'produit_create')]
    public function create(Request $request, EntityManagerInterface $manager): Response{
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($produit);
            $manager->flush();
            return $this->redirectToRoute('app_produit');
        }

        return $this->renderForm('produit/create.html.twig', [
            'form'=>$form
        ]);
    }

    #[Route('/admin/delete{id}', name: 'produit_delete')]
    public function delete(Produit $produit, EntityManagerInterface $manager){
        if ($produit){
            $manager->remove($produit);
            $manager->flush();
        }
        return $this->redirectToRoute('app_produit');
    }

    #[Route('/show/{id}', name: 'produit_show')]
    public function show(Produit $produit){
        return $this->render('produit/show.html.twig', [
            'produit'=>$produit
        ]);
    }
}
