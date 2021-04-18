<?php

namespace App\Component\Concurrent\Process;

use App\Component\Application\GameApplication;
use App\Component\Cache\Manager\CommandLineManager;
use App\Component\Cache\Serializable\Command;
use App\Component\Concurrent\Command\BaseCommand;
use App\Component\Exception\ExceptionFormatter;
use App\Tcp\Helper\Json;
use Throwable;

class CLIProcess extends BaseProcess
{
    public function getMain(): callable
    {
        return function ($process) {
            while (true) {
                try {
                    $cachedCommands = CommandLineManager::getInstance()->getList();
                    if (is_array($cachedCommands) && !empty($cachedCommands)) {
                        foreach ($cachedCommands as $cachedCommand) {

                            /** @var Command $commandObject */
                            $commandObject = unserialize($cachedCommand);

                            $commandObject->found = false;

                            /** @var BaseCommand $command */
                            foreach (GameApplication::commands() as $command) {
                                $command->bufferClear();
                                $command->input($commandObject->input);
                                if ($command->validCommand()) {
                                    $commandObject->found = true;
                                    $command->extractArguments();
                                    $result = $command->handle();
                                    GameApplication::app()->push($commandObject->fd, Json::encode([
                                        "cli-result" => $result
                                    ]));
                                    break;
                                }
                            }

                            if (!$commandObject->found) {
                                $cmd = explode(" ", trim($commandObject->input))[0];
                                GameApplication::app()->push($commandObject->fd, Json::encode([
                                    "cli-result" => [
                                        "text" => "Unknown command '$cmd'"
                                    ]
                                ]));
                            }

                            CommandLineManager::getInstance()->dispose($cachedCommand);
                        }
                    }
                } catch (Throwable $exception) {
                    dump("CLI PROCESS ERROR", ExceptionFormatter::toLogString($exception));
                    CommandLineManager::getInstance()->flush();
                }
            }
        };
    }
}