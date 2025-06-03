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

    public function countRecognizedByWord(User $user): array
    {
        $qb = $this->manager->createQueryBuilder();
        $qb->select('d.word')
            ->addSelect('SUM(CASE WHEN d.recognized = 1 THEN 1 ELSE 0 END) AS reconnus')
            ->addSelect('SUM(CASE WHEN d.recognized = 0 THEN 1 ELSE 0 END) AS non_reconnus')
            ->addSelect('COUNT(d.id) AS total')
            ->from(Drawing::class, 'd')
            ->join('d.game', 'g')
            ->where('g.user = :user')
            ->setParameter('user', $user)
            ->groupBy('d.word')
            ->orderBy('reconnus', 'DESC');

        return $qb->getQuery()->getResult();
    }
}