<?php

namespace App\Message\Handler;

use App\Message\ImportDrawingBatchMessage;
use App\Message\ProcessDrawingFileMessage;
use RuntimeException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
#[AsMessageHandler(fromTransport: 'async')]
readonly class ProcessDrawingFileMessageHandler
{

    public function __construct(
        private MessageBusInterface $bus
    ) {}

    public function __invoke(ProcessDrawingFileMessage $message): void
    {
        $batch = [];
        $batchSize = 10;

        $handle = fopen($message->getFilepath(), 'r');
        if (!$handle) {
            throw new RuntimeException('Cannot open file: ' . $message->getFilepath());
        }

        while (($line = fgets($handle)) !== false) {
            $data = json_decode($line, true);
            if (!$data) {
                continue;
            }

            $batch[] = $data;

            if (count($batch) >= $batchSize) {
                $this->bus->dispatch(new ImportDrawingBatchMessage($batch));
                $batch = [];
            }
        }

        if (!empty($batch)) {
            $this->bus->dispatch(new ImportDrawingBatchMessage($batch));
        }

        fclose($handle);
    }

}