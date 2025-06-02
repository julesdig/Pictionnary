<?php

namespace App\Manager;

use App\Entity\Drawing;
use App\Entity\Game;
use App\Entity\User;
use App\Repository\GameRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class GameManager
{

    public function __construct(
        private EntityManagerInterface $manager,
        private GameRepository $gameRepository,
    ) {
    }

    public function createGame(User $user,array $words): Game
    {
        $game = new Game();
        $game->setUser($user);
        $game->setScore(0);
        $game->setDate(new DateTime());
        foreach ($words as $word) {
            $drawing = new Drawing();
            $drawing->setWord($word);
            $this->manager->persist($drawing);
            $game->addDrawing($drawing);
        }

        $this->manager->persist($game);
        $this->manager->flush();

        return $game;
    }

    public function getGameByUser(User $user): array
    {
      return $this->gameRepository->findBy(['user' => $user],['score' => 'DESC']);
    }

}