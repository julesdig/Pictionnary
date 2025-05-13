<?php

namespace App\Form\Handler;

use App\Entity\User;
use App\Entity\Drawing;
use App\Message\ImportDrawingBatchMessage;
use App\Message\ProcessDrawingFileMessage;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ImportDrawingFormHandler
{
    public function __construct(
        private ParameterBagInterface $parameterBag,
        private readonly EntityManagerInterface $em,
        private readonly ValidatorInterface $validator,
        private readonly MessageBusInterface $bus
    ) {}

    public function handle(Request $request, FormInterface $form): ?true
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && $request->isMethod(Request::METHOD_POST)) {
            $file = $request->files->get('file');
            if ($file instanceof UploadedFile) {
                $filename=$file->getClientOriginalName();
                $directory = $this->parameterBag->get('kernel.project_dir').'/public/uploads/mass-import';
                $filePath = $directory . '/' . $filename;
                $file->move($directory, $filename);
                $this->bus->dispatch(New ProcessDrawingFileMessage($filePath));
                return true;
            }
        }

        return null;
    }

}