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

    public function save(Drawing $drawing): Drawing
    {
        $this->manager->persist($drawing);
        $this->manager->flush();
        $this->manager->refresh($drawing);
        return $drawing;
    }
}