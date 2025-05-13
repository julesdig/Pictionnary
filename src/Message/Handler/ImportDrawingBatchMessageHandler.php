<?php

namespace App\Message\Handler;


use App\Entity\Drawing;
use App\Message\ImportDrawingBatchMessage;
use DateMalformedStringException;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsMessageHandler(fromTransport: 'async')]
readonly class ImportDrawingBatchMessageHandler
{

    public function __construct(
        private EntityManagerInterface $manager,
        private ValidatorInterface $validator,
    ) {}

    /**
     * @throws DateMalformedStringException
     */
    public function __invoke(ImportDrawingBatchMessage $message)
    {

        foreach ($message->getDrawing() as $data) {
            $drawing = new Drawing();
            $drawing->setWord($data['word'] ?? null);
            $drawing->setCountrycode($data['countrycode'] ?? null);
            $drawing->setRecognized($data['recognized'] ?? null);
            $drawing->setTimestamp(new DateTime($data['timestamp']));
            $drawing->setDrawing($data['drawing'] ?? []);

            $errors = $this->validator->validate($drawing);
            if (count($errors) > 0) {
                continue;
            }

            $this->manager->persist($drawing);
        }

        $this->manager->flush();
        $this->manager->clear();

    }
}