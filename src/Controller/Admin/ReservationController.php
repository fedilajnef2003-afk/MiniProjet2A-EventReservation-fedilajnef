<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/reservation')]
#[IsGranted('ROLE_ADMIN')]
class ReservationController extends AbstractController
{
    #[Route('/event/{id}', name: 'admin_event_reservations', methods: ['GET'])]
    public function index(Event $event, ReservationRepository $reservationRepository): Response
    {
        $reservations = $reservationRepository->findBy(['event' => $event]);

        return $this->render('admin/reservation/index.html.twig', [
            'event' => $event,
            'reservations' => $reservations,
        ]);
    }
}
