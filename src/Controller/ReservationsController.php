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
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ReservationsController extends AbstractController
{


    #[Route('/reservations', name: 'app_reservations')]
    public function index(Request $request, EntityManagerInterface $manager, MailerInterface $mailer): Response
    {
        if (!$this->getUser()) {
            $this->addFlash('warning', 'Vous devez d\'abord vous connecter');
            return $this->redirectToRoute('app_login');
        }

        $reservation = new Reservations();
        // récupération de l'id utilisateur
        $user = $this->getUser();
        $reservation->setUserId($user);

        $form = $this->createForm(ReservationsType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $requestedDate = $formData->getRequestedDate();
            $quantity = $formData->getQuantity();
            $isIndoor = $formData->isIndoor();
            $isOutdoor = $formData->isOutdoor();

            $existingReservations = $manager->getRepository(Reservations::class)
                ->findBy(['requested_date' => $requestedDate, 'indoor' => $isIndoor, 'outdoor' => $isOutdoor]);

            $reservedSeats = 0;
            foreach ($existingReservations as $existingReservation) {
                $reservedSeats += $existingReservation->getQuantity();
            }

            $maxSeats = $isIndoor ? 120 : ($isOutdoor ? 80 : 0);
            $availableSeats = $maxSeats - $reservedSeats;

            if ($quantity > $availableSeats) {
                $this->addFlash('error', 'Désolé, il n\'y a pas assez de places disponibles.');
                return $this->redirectToRoute('app_reservations');
            }

            $manager->persist($reservation);
            $manager->flush();

            // Send confirmation email
            $userEmail = $user->getEmail();
            $emailContent = '<p>Votre reservation à bien été prise en compte.</p>';
            $emailContent .= '<p>Details de la reservation :</p>';
            $emailContent .= '<ul>';
            $emailContent .= '<li>Date de la réservation: ' . $reservation->getRequestedDate()->format('d-m-Y') . '</li>';
            $emailContent .= '<li>Nombre de personne: ' . $reservation->getQuantity() . '</li>';
            $emailContent .= '<li>Intérieur: ' . ($reservation->isIndoor() ? 'Oui' : 'Non') . '</li>';
            $emailContent .= '<li>Extérieur: ' . ($reservation->isOutdoor() ? 'Oui' : 'Non') . '</li>';
            $emailContent .= '</ul>';

            $email = (new Email())
                ->from('no_reply@example.com')
                ->to($userEmail)
                ->subject('Confirmation de votre reservation')
                ->html($emailContent);

            $mailer->send($email);

            return $this->redirectToRoute('app_reservation_success', ['id' => $reservation->getId()]);
        }

        return $this->render('reservations/reservation.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/reservation-success/{id}', name: 'app_reservation_success')]
    public function reservation_success(int $id, EntityManagerInterface $manager): Response
    {
        $reservation = $manager->getRepository(Reservations::class)->find($id);

        if (!$reservation) {
            throw $this->createNotFoundException('Reservation not found');
        }

        return $this->render('reservations/reservationsuccess.html.twig', [
            'reservation' => $reservation,
        ]);
    }
}