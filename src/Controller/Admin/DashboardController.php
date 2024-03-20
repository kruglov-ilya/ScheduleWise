<?php

namespace App\Controller\Admin;

use App\Entity\Booking;
use App\Entity\Service;
use App\Entity\ServiceCategory;
use App\Entity\Timeslot;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin_dashboard')]
    public function index(): Response
    {
        return $this->render('admin/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('ScheduleWiseAPI')

            // set this option if you prefer the page content to span the entire
            // browser width, instead of the default design which sets a max width
            ->renderContentMaximized()

            // set this option if you prefer the sidebar (which contains the main menu)
            // to be displayed as a narrow column instead of the default expanded design
            ->renderSidebarMinimized()

            // by default, users can select between a "light" and "dark" mode for the
            // backend interface. Call this method if you prefer to disable the "dark"
            // mode for any reason (e.g. if your interface customizations are not ready for it)
            ->disableDarkMode()

            // by default, all backend URLs are generated as absolute URLs. If you
            // need to generate relative URLs instead, call this method
            ->generateRelativeUrls();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Услуги', 'fas fa-list', Service::class);
        yield MenuItem::linkToCrud('Категории услуг', 'fas fa-list', ServiceCategory::class);
        yield MenuItem::linkToCrud('Доступное время', 'fas fa-list', Timeslot::class);
        yield MenuItem::linkToCrud('Записи на услуги', 'fas fa-list', Booking::class);
        // Добавьте дополнительные пункты меню для других сущностей
    }
}
