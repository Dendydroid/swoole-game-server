<?php

namespace App\Component\Concurrent\Command;

use App\Tcp\Constant\Command;
use JetBrains\PhpStorm\ArrayShape;

abstract class BaseCommand
{

    protected array $arguments = [];

    protected string $input;

    abstract public function getSignature(): string;

    abstract public function handle(): array;

    public function bufferClear(): void
    {
        $this->arguments = [];
        $this->input = "";
    }

    public function input(string $input): void
    {
        $this->input = $input;
    }

    public function get(string $argumentName): string
    {
        return str_replace(["'", ''], ['"', ''], $this->arguments[$argumentName] ?? "");
    }

    public function validCommand(): bool
    {
        return str_starts_with(trim($this->input), $this->getSignature());
    }

    public function extractArguments(): void
    {
        $arguments = explode(Command::ARGUMENTS_SEPARATOR, str_replace($this->getSignature(), "", trim($this->input)));
        foreach ($arguments as &$argument) {
            if (str_contains($argument, Command::ARGUMENT_VALUE_DELIMITER)) {
                [$argumentName, $argumentValue] = explode(Command::ARGUMENT_VALUE_DELIMITER, $argument);
                $this->arguments[$argumentName] = $argumentValue;
            }
        }
    }

    #[ArrayShape(["text" => "string"])] public function message(string $text): array
    {
        return [
            "text" => $text
        ];
    }
}