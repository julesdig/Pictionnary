<?php

namespace App\Message;
use Symfony\Component\Messenger\Attribute\AsMessage;

#[AsMessage('async')]
readonly class ProcessDrawingFileMessage
{
    public function __construct(
        private string $filepath,
    ) {}

    public function getFilepath(): string
    {
        return $this->filepath;
    }
}