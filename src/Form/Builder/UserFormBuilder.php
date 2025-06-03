<?php

namespace App\Form\Builder;

use App\Entity\User;
use App\Form\Type\AbstractDynamicFormType;
use App\Form\Type\UserFormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class UserFormBuilder
{

    private FormFactoryInterface $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function getForm(User $user): FormInterface
    {
        return $this->formFactory->createNamed(
            '',
            UserFormType::class,
            $user,
            [
                'method' => Request::METHOD_POST,
                'fields'=> [],
                'ignore_fields' => ['roles','games'],
            ]
        );
    }
}
