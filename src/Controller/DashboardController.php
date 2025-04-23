<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('', name: 'dashboard.')]
class DashboardController extends AbstractController
{
    #[Route('index', name: 'index')]
    public function index(): Response
    {

        return $this->render(
            'dashboard/index.html.twig',
            [
            ]
        );
    }
}
