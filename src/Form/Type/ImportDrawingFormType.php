<?php

declare(strict_types=1);

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
class ImportDrawingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('file', FileType::class, [
            'label' => 'Fichier .ndjson',
            'constraints' => [
                new File([
                    'maxSize' => '500M',
                    'extensions' => ['ndjson'],
                    'mimeTypes' => ['application/x-ndjson', 'application/json', 'text/plain'],
                    'mimeTypesMessage' => 'Veuillez uploader un fichier NDJSON valide.',
                ]),
            ],
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Importer',
            'attr' => ['class' => 'btn btn-primary'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
