<?php

namespace App\Controller;

use App\Entity\Drawing;
use App\Entity\Game;
use App\Entity\User;
use App\Manager\DrawingManager;
use App\Manager\GameManager;
use Aws\drs\drsClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

#[Route('', name: 'statistic.')]
class StatisticController extends AbstractController
{
    #[Route('statistic', name: 'index')]
    public function index(GameManager $gameManager, ChartBuilderInterface $chartBuilder, DrawingManager $drawingManager): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        $games= $gameManager->getGameByUser($user);

        $topGames = array_slice($games, 0, 5);
        /** @var Game $game */
        $labels = array_map(fn($game) => 'Game #' . $game->getId(), $topGames);
        $scores = array_map(fn($game) => $game->getScore(), $topGames);

        $ranked = $chartBuilder->createChart(Chart::TYPE_BAR);
        $ranked->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Top 5 Scores',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                    'data' => $scores,
                ],
            ],
        ]);
        $ranked->setOptions([
            'maintainAspectRatio' => false,
            'indexAxis' => 'y',
        ]);

        $averageScore = count($games) > 0 ? array_sum(array_map(fn($g) => $g->getScore(), $games)) / count($games) : 0;
        $drawings= $drawingManager->countRecognizedByWord($user);
        return $this->render('statistic/index.html.twig', [
            'games' => $games,
            'ranked' => $ranked,
            'averageScore' => $averageScore,
            'drawings' => $drawings,
        ]);
    }
}
