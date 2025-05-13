<?php

namespace App\Message;
use Symfony\Component\Messenger\Attribute\AsMessage;

#[AsMessage('async')]
readonly class ImportDrawingBatchMessage
{
    public function __construct(
        private array $drawing = []
    ) {}

    public function getDrawing(): array
    {
        return $this->drawing;
    }


}