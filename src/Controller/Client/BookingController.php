<?php

namespace App\Controller\Client;

use App\Entity\Booking;
use App\Entity\Service;
use App\Entity\Timeslot;
use App\Form\BookingFormType;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BookingController extends AbstractController
{
    #[Route('/booking', name: 'app_booking')]
    public function index(Request $request, BookingRepository $repository, EntityManagerInterface $entityManager): Response
    {
        $booking = new Booking();
        $form = $this->createForm(BookingFormType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($entityManager->find(Timeslot::class, $form->get('timeslot')->getData())->getCount() - 1 >= $repository->count(['timeslot' => $booking->getTimeslot()])){
                $booking->setPrice($entityManager->find(Service::class, $form->get('service')->getData())->getPrice());
                $entityManager->persist($booking);
                $entityManager->flush();

            }

        }

        return $this->render('booking/index.html.twig', [
            'bookingForm' => $form,
        ]);
    }
}