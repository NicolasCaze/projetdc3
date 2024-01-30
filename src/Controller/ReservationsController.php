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
            $formData = $form->getData();
    
            $requestedDate = $formData->getRequestedDate();
            $quantity = $formData->getQuantity();
            $isIndoor = $formData->isIndoor();
            $isOutdoor = $formData->isOutdoor();
    
            // Récupérer toutes les réservations existantes pour la date et le type spécifiés
            $existingReservations = $manager->getRepository(Reservations::class)
                ->findBy(['requested_date' => $requestedDate, 'indoor' => $isIndoor, 'outdoor' => $isOutdoor]);
    
            // Calculer le nombre de places déjà réservées
            $reservedSeats = 0;
            foreach ($existingReservations as $existingReservation) {
                $reservedSeats += $existingReservation->getQuantity();
            }
    
            // Vérifier si la nouvelle réservation peut être effectuée
            $maxSeats = $isIndoor ? 120 : ($isOutdoor ? 80 : 0);
            $availableSeats = $maxSeats - $reservedSeats;
    
            if ($quantity > $availableSeats) {
                // Il n'y a pas assez de places disponibles
                $this->addFlash('error', 'Désolé, il n\'y a pas assez de places disponibles.');
                return $this->redirectToRoute('app_reservations');
            }
    
            // La réservation peut être effectuée, persistez la réservation
            $reservation->setUserId($user);
            $manager->persist($reservation);
            $manager->flush();
    
            // Redirigez vers la page de réussite ou faites ce qui est nécessaire
            return $this->redirectToRoute('app_reservation_success');
        }
    
        return $this->render('reservations/reservation.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/reservation-success', name: 'app_reservation_success')]
    public function reservation_success(): Response{

        return $this->render('reservations/reservationsuccess.html.twig');
    }
}