<?php

namespace App\Repository;

use App\Entity\Drawing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

class DrawingRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Drawing::class);
    }

    public function findByCriteria(Criteria $criteria, string $select, ?string $group = null, ?array $order = null): Query
    {
        $query = $this->createQueryBuilder('d');

        $query
            ->select($select)
            ->addCriteria($criteria);

        if (!empty($group)) {
            $query->groupBy($group);
        }

        if (!empty($order)) {
            foreach ($order as $field => $sort) {
                match ($field) {
                    array_key_first($order) => $query->orderBy($field, $sort),
                    default => $query->addOrderBy($field, $sort)
                };
            }
        }

        return $query->getQuery();
    }
}
