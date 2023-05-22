<?php

namespace App\Controller\Admin;

use App\Entity\Answers;
use App\Entity\Questions;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN');

        //return parent::index();

        if (!$hasAccess)
            return $this->redirect('/');


        $routeBuilder = $this->container->get(AdminUrlGenerator::class);
        $url = $routeBuilder->setController(UserCrudController::class)->generateUrl();

        return $this->redirect($url);

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
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Laba Q&A');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Назад', 'fa fa-home', 'app_main');
        yield MenuItem::linkToCrud('Вопросы', 'fas fa-question', Questions::class);
        yield MenuItem::linkToCrud('Ответы', 'fas fa-answer', Answers::class);
        yield MenuItem::linkToCrud('Пользователи', 'fas fa-user', User::class);
    }
}
