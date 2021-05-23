<?php

namespace App\Tcp\Controller;

use App\Component\Application\GameApplication;
use App\Component\Connection\ClientConnection;
use App\Tcp\Helper\Json;

abstract class BaseController
{
    public ?ClientConnection $connection;

    public function setConnection(int $fd): static
    {
        $app = GameApplication::app();
        if ($app) {
            $this->connection = $app->getConnection($fd);
        }
        return $this;
    }

    public function response(array|string $data): void
    {
        $app = GameApplication::app();
        if ($app) {
            $responseData = is_array($data) ? Json::encode($data) : $data;
            $app->push($this->connection->getFd(), $responseData);
        }
    }
}