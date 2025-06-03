<?php

namespace App\Form\Builder;

use App\Entity\User;
use App\Form\Type\StartGameFormType;
use App\Form\Type\UserFormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class StartGameFormBuilder
{
    private FormFactoryInterface $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function getForm(): FormInterface
    {
        return $this->formFactory->createNamed(
            '',
            StartGameFormType::class,
            null,
            [
                'method' => Request::METHOD_POST,
            ]
        );
    }

}