<?php

namespace App\Manager;

use App\Entity\Interest;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class InterestManager
{
    public function __construct(
        private EntityManagerInterface $manager,

    ) {
    }

    public function create(string $name): Interest
    {
        $interest = new Interest();
        $interest->setName($name);
        return $this->save($interest);

    }

    public function save(Interest $interest): Interest
    {
        $this->manager->persist($interest);
        $this->manager->flush();
        return $interest;
    }
}