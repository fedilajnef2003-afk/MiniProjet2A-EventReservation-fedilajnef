<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Reservation;
use App\Form\ReservationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/reservation')]
#[IsGranted('ROLE_USER')]
final class ReservationController extends AbstractController
{
    #[Route('/new/{id}', name: 'reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        // Check if seats are available
        if ($event->getSeats() <= 0) {
            $this->addFlash('error', 'Désolé, cet événement est complet.');
            return $this->redirectToRoute('event_show', ['id' => $event->getId()]);
        }

        $reservation = new Reservation();
        $reservation->setEvent($event);
        $reservation->setCreatedAt(new \DateTime());

        // Pre-fill if we have a user
        if ($this->getUser()) {
            $reservation->setName($this->getUser()->getUserIdentifier());
        }

        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Decrement available seats
            $event->setSeats($event->getSeats() - 1);

            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('reservation_success');
        }

        return $this->render('reservation/new.html.twig', [
            'event' => $event,
            'reservationForm' => $form->createView(),
        ]);
    }

    #[Route('/success', name: 'reservation_success', methods: ['GET'])]
    public function success(): Response
    {
        return $this->render('reservation/success.html.twig');
    }
}
