<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }


    #[Route('/home/testmail', name: 'app_home_testmail')]
    public function testmail(MailerInterface $mailer, Environment $twig){
        $html = $twig->render('home/mail.html.twig', [
            'var'=>'ceci est une facture'
        ]);

        $email = (new Email())
            ->from()
            ->to('lucj6778@gmail.com')
            ->subject('Envoie de mail opérationnel.')
            ->text('Je peut envoyer des email automatiquement :)');
        $mailer->send($email);
        dd($email);
        $this->addFlash('success', 'mail envoyé');
        return $this->redirectToRoute('app_produit');
    }
}
