<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\User;
use App\Form\Builder\StartGameFormBuilder;
use App\Form\Handler\StartGameFormHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('', name: 'dashboard.')]
class DashboardController extends AbstractController
{
    #[Route('index', name: 'index')]
    public function index( Request $request,StartGameFormBuilder $startGameFormBuilder, StartGameFormHandler $startGameFormHandler): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }
        $form=$startGameFormBuilder->getForm();
        $result = $startGameFormHandler->handle($request,$form, $user);
        if ($result instanceof Game) {
            return $this->redirectToRoute('game.start', [
                'id' => $result->getId(),
            ]);
        }

        return $this->render('dashboard/index.html.twig', [
                'form' => $form->createView(),
            ]
        );
    }
}
