<?php

namespace App\Manager;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserManager
{
    public function __construct(
        private EntityManagerInterface $manager,

    ) {
    }

    public function create(string $firstName,string $lastName ,string $location ,int $age ): User
    {
        $user = new User();
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setAge($age);
        $user->setLocation($location);
        $this->manager->persist($user);
        $this->manager->flush();
        return $user;
    }
}