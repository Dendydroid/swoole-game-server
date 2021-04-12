<?php

namespace App\Tcp\Controller;

use App\Component\Cache\Manager\CommandLineManager;
use App\Component\Cache\Serializable\Command;

class CommandController extends BaseController
{
    public function input(array $data): void
    {

        CommandLineManager::service()->queue(new Command($data["input"], $this->connection->getFd()));
    }
}