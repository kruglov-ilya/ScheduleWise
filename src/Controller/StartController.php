<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;

class StartController extends AbstractController
{
    #[Route(path: '/', name: 'app_start')]
    public function index(Security $security): RedirectResponse
    {
        if ($security->isGranted('ROLE_ADMIN'))
            return $this->redirectToRoute('admin_dashboard');
        else
            return $this->redirectToRoute('app_booking');
    }
}