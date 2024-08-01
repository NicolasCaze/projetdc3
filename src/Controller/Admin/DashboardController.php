<?php

namespace App\Controller\Admin;

use App\Entity\Attachments;
use App\Entity\Menus;
use App\Entity\Orders;
use App\Entity\ProductCategories;
use App\Entity\Products;
use App\Entity\Reservations;
use App\Entity\Settings;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Le Hameau')
            ->renderContentMaximized();
    }
    public function configureCrud(): Crud
    {

        $crud = parent::configureCrud();

        $crud->renderContentMaximized();
        $crud->showEntityActionsInlined();

        return $crud;
    }
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Données');
        yield MenuItem::linkToCrud('Utilisateur', 'fa-solid fa-user', User::class);
        yield MenuItem::linkToCrud('Menus', 'fa-solid fa-clipboard', Menus::class);
        yield MenuItem::linkToCrud('Reservations', 'fa-solid fa-utensils', Reservations::class);
        yield MenuItem::linkToCrud('Commande', 'fa-solid fa-truck-ramp-box', Orders::class);

        yield MenuItem::section('Sous-données');
        yield MenuItem::linkToCrud('Products', 'fa-solid fa-box-archive', Products::class);
        yield MenuItem::linkToCrud('ProductCategories', 'fa-solid fa-boxes-stacked', ProductCategories::class);
        yield MenuItem::linkToCrud('Attachments', 'fa-solid fa-image', Attachments::class);
        yield MenuItem::linkToCrud('Settings', 'fa-solid fa-gear', Settings::class);

    }
}
