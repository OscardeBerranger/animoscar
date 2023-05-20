<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Produit;
use App\Form\ImageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImageController extends AbstractController
{
    #[Route('/admin/image/{id}', name: 'app_image')]
    public function index(Produit $produit): Response
    {

        $image = new Image();
        $form = $this->createForm('App\Form\ImageType', $image);

        return $this->renderForm('image/index.html.twig', [
            'produit'=>$produit,
            'form'=>$form
        ]);
    }


    #[Route('/addtoproduit/{id}', name: 'app_produit_image')]
    #[Route('/addtoprofile', name: 'app_profile_image')]
    public function addImage(Request $request, Produit $produit, EntityManagerInterface $manager){
        $route = $request->attributes->get('_route');

        $image = new Image();
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            if ($produit && $route == 'app_produit_image'){
                $image->setProduit($produit);
            }elseif ($route == "app_profile_add"){
                $oldImage = $this->getUser()->getProfile()->getImage();
                if ($oldImage){
                    $manager->remove($oldImage);
                }
                $image->setProfile($this->getUser()->getProfile());
            }
            $manager->persist($image);
            $manager->flush();
        }
        if ($route == "app_prodilfe_image"){
            return $this->redirectToRoute('app_profile');
        }
        return $this->redirectToRoute('app_image', ['id'=>$produit->getId()]);
    }


    #[Route('/admin/removefromproduit/{id}', name:'app_image_removefromproduit')]
    public function removeFromProduit(Image $image, EntityManagerInterface $manager): Response{

        if ($image){
            $produit = $image->getProduit();
            $manager->remove($image);
            $manager->flush();
        }
        return $this->redirectToRoute('app_image', ['id'=>$produit->getId()], Response::HTTP_SEE_OTHER);
    }


}
