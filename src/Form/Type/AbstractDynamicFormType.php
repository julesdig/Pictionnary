<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Form\Guesser\FieldTypeGuesser;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class AbstractDynamicFormType extends AbstractType
{


    public function __construct(
        private readonly FieldTypeGuesser $fieldTypeGuesser,
        private readonly TranslatorInterface $translator
    ) {
    }

    /**
     * @throws ReflectionException
     */
    protected function generateLabel(string $property): string
    {
        $className = static::getEntityClass();
        $shortClassName = new ReflectionClass($className)->getShortName();
        return $shortClassName . '.' . $property;
    }
    /**
     * @throws ReflectionException
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $fields = $options['fields'];
        if (empty($fields)) {
            $refClass = new ReflectionClass(static::getEntityClass());
            $fields = array_map(fn($prop) => $prop->getName(), $refClass->getProperties());
        }

        $ignoreFields = array_merge($options['ignore_fields'], ['id']);
        if (!empty($ignoreFields)) {
            $fields = array_diff($fields, $ignoreFields);
        }

        foreach ($fields as $field) {
            match ($field) {
                'password' => $builder->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'invalid_message' => 'The password fields must match.',
                    'options' => ['attr' => ['class' => 'password-field']],
                    'required' => true,
                    'first_options'  => [
                        'label' => $this->generateLabel('password'),
                        'label_attr' => ['class' => 'sub_title' ],
                        'attr' => ['placeholder' => $this->generatePlaceholder('password'), 'class' => 'form_style']
                    ],
                    'second_options' => [
                        'label' => $this->generateLabel('labelrepeatPassword'),
                        'label_attr' => ['class' => 'sub_title' ],
                        'attr' => [
                            'placeholder' => $this->generatePlaceholder('repeatPassword'),
                            'class' => 'form_style',
                        ],
                    ],
                ]),
                default => $builder->add(
                    $field,
                    $this->fieldTypeGuesser->guessFormType(static::getEntityClass(), $field),
                    [
                        'label' => $this->generateLabel($field),
                        'label_attr' => ['class' => 'sub_title' ],
                    'attr' => [
                        'placeholder' => $this->generatePlaceholder($field),
                        'class' => 'form_style',
                        ],
                    ]
                ),
            };
            $builder->add('save', SubmitType::class, [
                'label' => $this->translator->trans('button.submit'),
                'attr' => ['class' => 'btn'],
            ]);
        }
    }

    /**
     * @throws ReflectionException
     */
    protected function generatePlaceholder(string $property): string
    {
        return $this->translator->trans('placeholder')
            .' '.$this->translator->trans($this->generateLabel($property));
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'fields' => [],
            'data_class' => static::getEntityClass(),
            'ignore_fields' => [],
        ]);
    }
}
