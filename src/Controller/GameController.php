<?php

namespace App\Controller;

use App\Entity\Drawing;
use App\Entity\Game;
use App\Entity\User;
use App\Manager\DrawingManager;
use App\Manager\GameManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

        $game = $gameManager->createGame($user, $randomWords);

        return $this->render('game/index.html.twig', [
                'game' => $game,
            ]
        );
    }

    #[Route('/api/drawing/{id}', name: 'api_save_drawing', methods: ['POST'])]
    public function saveDrawing(
        Request $request, 
        Drawing $drawing, 
        DrawingManager $drawingManager,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->json(['error' => 'Invalid data'], 400);
        }

        // Update the drawing with the data
        $drawing->setDrawing(['data' => $data['drawing']]);
        $drawing->setRecognized($data['recognized']);
        $drawing->setIsStarted(true);

        // Save the drawing
        $drawingManager->save($drawing);

        return $this->json(['success' => true, 'id' => $drawing->getId()]);
    }

    #[Route('/api/game/{id}', name: 'api_save_game_score', methods: ['POST'])]
    public function saveGameScore(
        Request $request, 
        Game $game,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['score'])) {
            return $this->json(['error' => 'Score is required'], 400);
        }

        // Update the game score
        $game->setScore($data['score']);

        // Save the game
        $entityManager->persist($game);
        $entityManager->flush();

        return $this->json(['success' => true, 'id' => $game->getId(), 'score' => $game->getScore()]);
    }

    #[Route('/game/{id}/recap', name: 'recap')]
    public function recap(Game $game): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
           throw $this->createAccessDeniedException();
        }

        // Check if the game belongs to the current user
        if ($game->getUser() !== $user) {
            throw $this->createAccessDeniedException('You can only view your own game recaps');
        }

        return $this->render('game/recap.html.twig', [
            'game' => $game,
        ]);
    }
}
