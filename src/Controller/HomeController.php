<?php

namespace App\Controller;

use App\Entity\NewsletterSubscriber;
use App\Form\NewsletterSubscriberType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, EntityManagerInterface $em, MailerInterface $mailer): Response
    {
        $subscriber = new NewsletterSubscriber();
        $form = $this->createForm(NewsletterSubscriberType::class, $subscriber);

        // Gérer la soumission du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($subscriber);
            $em->flush();

            $this->addFlash('success', 'Votre abonnement à bien était pris en compte');

            $email = (new Email())
                ->from('no-reply@adapei-newsletter.com')
                ->to('user@example.com')
                ->subject('Inscription Newsletter')
                ->text('Confirmation de votre inscription à notre newsletter')
                ->html('<p>Confirmation de votre inscription à notre newsletter</p>');

            $mailer->send($email);

            return $this->redirectToRoute('app_home');
        }

        // Passer le formulaire à la vue
        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
