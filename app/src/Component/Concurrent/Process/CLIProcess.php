<?php

namespace App\Component\Concurrent\Process;

use App\Component\Cache\Manager\CommandLineManager;
use App\Component\Cache\Serializable\Command;
use App\Component\Concurrent\Command\BaseCommand;
use App\Component\Exception\ExceptionFormatter;
use App\Component\Service\SharedApplicationData;
use App\Component\Service\SharedServer;
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

                            $appData = new SharedApplicationData();
                            $server = (new SharedServer())->getServer();
                            if ($server !== null) {
                                $server = $server->getServer();
                            }
                            /** @var BaseCommand $command */
                            foreach ($appData->getData()->commands as $command) {
                                $command->bufferClear();
                                $command->input($commandObject->input);
                                if ($command->validCommand()) {
                                    $commandObject->found = true;
                                    $command->extractArguments();
                                    $result = $command->handle();
                                    if ($server !== null) {
                                        $server->push($commandObject->fd, Json::encode([
                                            "cli-result" => $result
                                        ]));
                                    }
                                    break;
                                }
                            }

                            if (!$commandObject->found) {
                                $cmd = explode(" ", trim($commandObject->input))[0];
                                $server->push($commandObject->fd, Json::encode([
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