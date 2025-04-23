<?php

namespace App\Form\Handler;

use App\Entity\User;
use App\Manager\UserManager;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class UserFormHandler
{
    public function __construct(
        private UserManager $userManager,
        private UserPasswordHasherInterface $userPasswordHasher
    ) {
    }

    /**
     * @throws ORMException
     */
    public function handle(Request $request, FormInterface $form): ?User
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && $request->isMethod(Request::METHOD_POST)) {
            $user = $form->getData();
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $user->getPassword()));

            return $this->userManager->save($user);
        }
        return null;
    }
}
