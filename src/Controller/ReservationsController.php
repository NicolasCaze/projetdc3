<?php

namespace App\Controller;

use App\Entity\Reservations;
use App\Form\ReservationsType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationsController extends AbstractController
{
    #[Route('/reservations', name: 'app_reservations')]
    public function index(Request $request, EntityManagerInterface $manager)
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $reservation = new Reservations();
        // récupération de l'id utilisateur
        $user = $this->getUser();
        $reservation->setUserId($user);
        // -------- //
        
        $form = $this->createForm(ReservationsType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $manager ->persist($reservation);
            $manager->flush();
            
            return $this->redirectToRoute('app_reservation_success');
        }


        return $this->render('reservations/reservation.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/reservation-success', name: 'app_reservation_success')]
    public function reservation_success(): Response{

        return $this->render('reservations/reservationsuccess.html.twig');
    }
}