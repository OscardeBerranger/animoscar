<?php

namespace App\Controller;

use App\Entity\Addresse;
use App\Form\AddresseType;
use App\Repository\AddresseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/addresse')]
class AddresseController extends AbstractController
{
    #[Route('/', name: 'app_addresse_index', methods: ['GET'])]
    public function index(AddresseRepository $addresseRepository): Response
    {
        return $this->render('addresse/index.html.twig', [
            'addresses' => $addresseRepository->findBy([
                'profile'=>$this->getUser()->getProfile()
            ]),
        ]);
    }

    #[Route('/new', name: 'app_addresse_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AddresseRepository $addresseRepository): Response
    {
        $addresse = new Addresse();
        $form = $this->createForm(AddresseType::class, $addresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $addresse->setProfile($this->getUser()->getProfile());
            $addresseRepository->save($addresse, true);
            return $this->redirectToRoute('app_addresse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('addresse/new.html.twig', [
            'addresse' => $addresse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_addresse_show', methods: ['GET'])]
    public function show(Addresse $addresse): Response
    {
        return $this->render('addresse/show.html.twig', [
            'addresse' => $addresse,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_addresse_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Addresse $addresse, AddresseRepository $addresseRepository): Response
    {
        $form = $this->createForm(AddresseType::class, $addresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $addresseRepository->save($addresse, true);

            return $this->redirectToRoute('app_addresse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('addresse/edit.html.twig', [
            'addresse' => $addresse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_addresse_delete', methods: ['POST'])]
    public function delete(Request $request, Addresse $addresse, AddresseRepository $addresseRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$addresse->getId(), $request->request->get('_token'))) {
            $addresseRepository->remove($addresse, true);
        }

        return $this->redirectToRoute('app_profile', [], Response::HTTP_SEE_OTHER);
    }
}
