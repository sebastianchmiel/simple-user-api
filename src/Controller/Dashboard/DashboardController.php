<?php

namespace App\Controller\Dashboard;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * dashboard controller
 * 
 * @author Sebastian Chmiel <s.chmiel2@gmail.com>
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     * 
     * show dashboard
     * 
     * @return Response
     */
    public function dashboard(): Response
    {
        return $this->render('dashboard/dashboard.html.twig');
    }
}
