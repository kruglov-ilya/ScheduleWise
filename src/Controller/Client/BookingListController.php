<?php

namespace App\Controller\Client;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BookingListController extends AbstractController
{
    #[Route('/profile/booking-list', name: 'app_booking_list')]
    public function index(Security $security): Response
    {
        /**
         * @var User $user
         */
        $user = $security->getUser();

        $bookings = $user->getBookings()->toArray();

        return $this->render('booking_list/index.html.twig', [
            'controller_name' => 'BookingListController',
            'bookings' => $bookings
        ]);
    }
}
