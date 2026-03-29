<?php

namespace App\Controller\Admin;

use App\Repository\EventRepository;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class DashboardController extends AbstractController
{
    #[Route('', name: 'admin_dashboard')]
    public function index(EventRepository $eventRepository, ReservationRepository $reservationRepository): Response
    {
        $events = $eventRepository->findAll();
        $reservations_count = count($reservationRepository->findAll());

        return $this->render('admin/dashboard/index.html.twig', [
            'events' => $events,
            'reservations_count' => $reservations_count,
        ]);
    }
}
