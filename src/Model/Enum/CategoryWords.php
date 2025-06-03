<?php

namespace App\Model\Enum;

use App\Entity\Category;

enum CategoryWords
{
    public const array WORDS = [
        'Formes et symboles' => ['zigzag', 'octagon', 'diamond'],
        'Objets du quotidien' => ['crayon', 'envelope'],
        'Corps humain et vêtements' => ['eye', 'pants'],
        'Nature & transport' => ['mushroom', 'wheel', 'airplane'],
    ];

    public static function getWordsForCategoryName(Category $category): array
    {
        return self::WORDS[$category->getName()] ?? [];
    }


}
