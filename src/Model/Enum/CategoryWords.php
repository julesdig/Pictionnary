<?php

namespace App\Model\Enum;

use App\Entity\Category;

enum CategoryWords
{
    public const array WORDS = [
        'Formes et symboles' => ['zigzag', 'octagon', 'diamond','triangle', 'square'],
        'Objets du quotidien' => ['crayon', 'envelope', 'scissor', 'spoon', 'hammer'],
        'Corps humain et vêtements' => ['eye', 'pants', 'hat',  'shoe', 't-shirt'],
        'Nature & transport' => ['mushroom', 'wheel', 'airplane', 'train', 'flower' ],
    ];

    public static function getWordsForCategoryName(Category $category): array
    {
        return self::WORDS[$category->getName()] ?? [];
    }


}
