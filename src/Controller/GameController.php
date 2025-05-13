<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\User;
use App\Manager\DrawingManager;
use App\Manager\GameManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('', name: 'game.')]
class GameController extends AbstractController
{
    #[Route('start', name: 'start')]
    public function create(DrawingManager $drawingManager, GameManager $gameManager): Response
    {

        $user = $this->getUser();
        if (!$user instanceof User) {
           throw $this->createAccessDeniedException();
        }

        $words = $drawingManager->findDistinctWords();
        $words = array_column($words, 'word');
        shuffle($words);
        $randomWords = array_slice($words, 0, 5);

        $game=$gameManager->createGame($user,$randomWords);

        return $this->render('game/index.html.twig', [
                'game' => $game,
            ]
        );
    }
}