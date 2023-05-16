<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    #[Route('/admin/categorie', name: 'app_categorie')]
    public function create(Request $request, EntityManagerInterface $manager): Response{
        $category = new Categorie();
        $form = $this->createForm(CategorieType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $manager->persist($category);
            $manager->flush();
        }

        return $this->renderForm('categorie/create.html.twig', [
            'categoryForm'=>$form
        ]);
    }
}
