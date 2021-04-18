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
        $this->connection = GameApplication::getConnection($fd);
        return $this;
    }

    public function response(array|string $data): void
    {
        $responseData = is_array($data) ? Json::encode($data) : $data;
        GameApplication::app()->push($this->connection->getFd(), $responseData);
    }
}