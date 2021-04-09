<?php

namespace App\Tcp\Packet;

use App\Tcp\Helper\Id;
use App\Tcp\Helper\Json;
use Symfony\Component\Uid\Uuid;

abstract class BasePacket
{
    protected string $id;
    protected string $action;
    protected array $data;

    public function __construct()
    {
        $this->id = Id::generateId(microtime());
    }

    public function getName(): string
    {
        return static::class;
    }

    public function __toString(): string
    {
        return Json::encode([
            "id" => $this->id,
            "name" => $this->getName(),
            "action" => $this->action,
            "data" => $this->data,
        ]);
    }
}
