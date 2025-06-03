<?php

namespace App\Form\Handler;

use App\Entity\Game;
use App\Entity\User;
use App\Manager\DrawingManager;
use App\Manager\GameManager;
use App\Manager\UserManager;
use App\Model\Enum\CategoryWords;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class StartGameFormHandler
{
    public function __construct(
        private DrawingManager $drawingManager,
        private GameManager $gameManager,
    ) {
    }
    public function handle(Request $request, FormInterface $form, User $user): ?Game
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && $request->isMethod(Request::METHOD_POST)) {
            $data = $form->getData();
            $category = $data['category'] ?? null;
            $words = match(true){
                $category === null =>array_column( $this->drawingManager->findDistinctWords(), 'word'),
                default => CategoryWords::getWordsForCategoryName($category),
            };

            shuffle($words);
            $randomWords = array_slice($words, 0, 5);

            return $this->gameManager->createGame($user, $randomWords);
        }
        return null;
    }

}