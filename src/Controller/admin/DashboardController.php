<?php

namespace App\Controller\admin;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('dashboard/', name: 'admin_dashboard.')]
class DashboardController extends AbstractController
{

    #[Route('index', name: 'index')]
    public function index(
        Request $request ,

    ): Response
    {
        return $this->render('admin/dashboard/index.html.twig', [

        ]);
    }


}