<?php

namespace App\Manager;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;

readonly class UserManager
{
    public function __construct(
        private EntityManagerInterface $manager,
    ) {
    }

    /**
     * @throws ORMException
     */
    public function save(User $user): User
    {
        $this->manager->persist($user);
        $this->manager->flush();
        $this->manager->refresh($user);
        return $user;
    }
}
