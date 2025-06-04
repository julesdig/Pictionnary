<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Drawing;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class DrawingFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $drawings = [

               'zigzag', 'octagon', 'diamond','triangle', 'square',
        'crayon', 'envelope', 'scissor', 'spoon', 'hammer',
      'eye', 'pants', 'hat',  'shoe', 't-shirt',
        'mushroom', 'wheel', 'airplane', 'train', 'flower' ];
        foreach ($drawings as $drawing) {
            $drawingEntity = new Drawing();
            $drawingEntity->setWord($drawing);
            $manager->persist($drawingEntity);
        }
        $manager->flush();
    }
}