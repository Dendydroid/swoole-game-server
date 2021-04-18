<?php

namespace App\Tcp\Packet;

class LoadPacket extends BasePacket
{
    protected string $id;
    protected string $action;
    protected array $data;

    public const ACTION_NAME = "load";

    public function __construct(array $data)
    {
        parent::__construct();
        $this->action = static::ACTION_NAME;
        $this->data = $data;
    }
}
