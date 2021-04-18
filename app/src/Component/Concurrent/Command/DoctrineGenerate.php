<?php

namespace App\Component\Concurrent\Command;

use JetBrains\PhpStorm\ArrayShape;

class DoctrineGenerate extends BaseCommand
{
    public function getSignature(): string
    {
        return "doctrine:generate";
    }

    #[ArrayShape(["text" => "string"])] public function handle(): array
    {
        $command = "php " . PROJECT_PATH . "/vendor/bin/doctrine orm:schema-tool:update --force";
        return $this->message(shell_exec($command) ?? "NO_RESPONSE");
    }

}