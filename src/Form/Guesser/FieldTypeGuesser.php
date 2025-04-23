<?php

namespace App\Form\Guesser;

use DateTime;
use DateTimeInterface;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\PropertyInfo\PropertyInfoExtractorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

readonly class FieldTypeGuesser
{

    public function __construct(private PropertyInfoExtractorInterface $propertyInfo) {}

    public function guessFormType(string $className, string $property): ?string
    {

        $types = $this->propertyInfo->getTypes($className, $property);

        if (empty($types)) {
            return TextType::class;
        }

        $type = $types[0];
        $builtinType = $type->getBuiltinType();

        return match ($property) {
            'email' => EmailType::class,
            'password' =>
            RepeatedType::class,
            default => match ($builtinType) {
                'int', 'float' => NumberType::class,
                'bool' => CheckboxType::class,
                'object' => match ($type->getClassName()) {
                    DateTimeInterface::class, DateTime::class => DateTimeType::class,
                    default => TextType::class
                },
                default => TextType::class
            }
        };
    }
}