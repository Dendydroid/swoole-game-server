<?php

namespace App\Component\Concurrent\Command;

class HelpCommand extends BaseCommand
{
    public array $help = [
        "register" => "This command is for registering a user. register e=<email> p=<password>",
        "*" => "List of commands:\n- register\n"
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

        $for = $this->get("?");

        if ($for !== '') {
            return [
                "text" => $this->getHelpText($this->get("for"))
            ];
        }
        return [
            "text" => "You have to specify a `?` argument like so (for command `start`): \nhelp ?=start"
        ];
    }

}