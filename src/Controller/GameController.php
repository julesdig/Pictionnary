<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('', name: 'game.')]
class GameController extends AbstractController
{
    #[Route('start', name: 'start')]
    public function create(): Response
    {
dd("game");
        return $this->render('dashboard/index.html.twig', [

            ]
        );
    }
}