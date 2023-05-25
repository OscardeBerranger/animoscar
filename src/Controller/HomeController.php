<?php

namespace App\Controller;

use App\Entity\Order;
use Knp\Snappy\Pdf;
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


    #[Route('/home/testmail/{id}', name: 'app_home_testmail')]
    public function testmail(Order $order, MailerInterface $mailer, Environment $twig, Pdf $pdfmaker){

        $html = $twig->render('home/facture.html.twig',[
            'order'=> $order
        ]);
        $pdf = $pdfmaker->getOutputFromHtml($html);
        $email = (new Email())
            ->from('contact@oscadeberranger.com')
            ->to($order->getProfile()->getOfUser()->getEmail())
            ->subject('Commande récente pour votre animal.')
            ->text('Voici la facture de votre commande récente sure animoscaro.oscadeberranger.com merci et à bientot :)')
            ->attach($pdf, sprintf('facture.pdf'));
        $mailer->send($email);
        $this->addFlash('success', 'mail envoyé');
        return $this->redirectToRoute('app_produit');
    }

    #[Route('/home/makepdf/{id}', name: 'app_home_makepdf')]
    public function makePdf(Order $order, MailerInterface $mailer, Pdf $pdfmaker, Environment $twig){
        $html = $twig->render('home/facture.html.twig',[
            'order'=> $order
        ]);
        $pdf = $pdfmaker->getOutputFromHtml($html);
        $pdfmaker->generateFromHtml($html, "pdfs/".$order->getId().".pdf");
        return $this->render('home/facture.html.twig',[
            'order'=>$order
        ]);
    }
}
