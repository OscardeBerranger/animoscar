<?php

namespace App\Controller;

use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/myProfile', name: 'app_profile')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig');
    }

    #[Route('/editProfile', name: 'profile_edit')]
    public function edit(Request $request, EntityManagerInterface $manager){

        $profile = $this->getUser()->getProfile();

        $form = $this->createForm(ProfileType::class, $profile);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $manager->persist($profile);
            $manager->flush();
            return $this->redirectToRoute('app_profile');
        }
        return $this->renderForm('profile/edit.html.twig', [
            'form'=>$form
        ]);

    }
}
