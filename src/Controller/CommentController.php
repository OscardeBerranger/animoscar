<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Produit;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    #[Route('/comment', name: 'app_comment')]
    public function index(): Response
    {
        return $this->render('comment/index.html.twig', [
            'controller_name' => 'CommentController',
        ]);
    }
    #[Route('/comment/create{id}', name: 'comment_create')]
    public function create(Produit $produit, Request $request, EntityManagerInterface $manager){
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $comment->setCreator($this->getUser());
            $comment->setProduit($produit);
            $manager->persist($comment);
            $manager->flush();
            return $this->render('produit/show.html.twig', [
                'produit'=>$produit,
                'id'=>$produit->getId()
            ]);
        }
        return $this->renderForm('comment/create.html.twig', [
            'commentForm'=>$form
        ]);
    }

    #[Route('/comment/delete/{id}', name: 'app_comment_delete')]
    public function delete(Comment $comment, EntityManagerInterface $manager){
        $redirectId = $comment->getProduit()->getId();
        if (!$this->getUser()){
            $this->addFlash('danger', 'Vous devez être connecté afin de supprimer un commentaire');
            return $this->redirectToRoute('app_login');
        }
        if ($comment){
            $manager->remove($comment);
            $manager->flush();
        }
        return $this->redirectToRoute('produit_show', ['id'=>$redirectId]);
    }
}
