<?php

namespace App\Manager;

use App\Entity\Drawing;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class DrawingManager
{
    public function __construct(
        private EntityManagerInterface $manager,
    ) {
    }

    public function findDistinctWords():array
    {
        $query = $this->manager->createQueryBuilder()
            ->select('DISTINCT d.word')
            ->from(Drawing::class, 'd')
            ->getQuery();

        return $query->getResult();
    }
    public function save(Drawing $drawing): Drawing
    {
        $this->manager->persist($drawing);
        $this->manager->flush();
        $this->manager->refresh($drawing);
        return $drawing;
    }
}