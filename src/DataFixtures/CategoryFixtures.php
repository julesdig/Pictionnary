<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class CategoryFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $categoryes = [
            "Formes et symboles",
            "Objets du quotidien",
            "Corps humain et vêtements",
            "Nature & transport",
        ];
        foreach ($categoryes as $category) {
                    $categoryEntity = new Category();
                    $categoryEntity->setName($category);
                    $manager->persist($categoryEntity);
        }
        $manager->flush();
    }
}