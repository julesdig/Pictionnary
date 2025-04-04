<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('draw/', name: 'draw.')]
class DrawController extends AbstractController
{

    #[Route('test', name: 'test')]
    public function index(): Response
    {
        $filePath = $this->getParameter('kernel.project_dir') . '/drawings.ndjson';

        // Vérifier si le fichier existe
        if (!file_exists($filePath)) {
            return $this->json(['error' => 'Fichier non trouvé'], 404);
        }

        // Lire le fichier ligne par ligne
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $drawings = array_map(fn($line) => json_decode($line, true), $lines);

        return $this->json($drawings);
    }


}