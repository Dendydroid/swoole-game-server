<?php

namespace App\Component\Concurrent\Command;

class HelpCommand extends BaseCommand
{
    public array $help = [
        "?" => "This is the CLI for the Game Server :)"
    ];

    private function getHelpText(string $command)
    {
        return $this->help[$command] ?? ("Cannot help for command `" . $command . "`. Unknown command");
    }

    public function getSignature(): string
    {
        return "help";
    }

    public function handle(): array
    {

        $for = $this->get("for");

        if ($for !== '') {
            return [
                "text" => $this->getHelpText($this->get("for"))
            ];
        }
        return [
            "text" => "You have to specify a `for` argument like so (for command `start`): \nhelp for=start"
        ];
    }

}