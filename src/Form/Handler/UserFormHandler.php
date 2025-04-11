<?php

namespace App\Form\Handler;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class UserFormHandler
{
    public function handle(Request $request,FormInterface $form): null
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && $request->isMethod(Request::METHOD_POST)) {
            $user = $form->getData();
            dd($user);
        }
        return null;

    }
}