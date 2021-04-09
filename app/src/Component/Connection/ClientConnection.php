<?php

namespace App\Component\Connection;

use App\Component\Room\BaseRoom;

class ClientConnection extends BaseConnection
{
    protected $activeRoom = null;

    public function getActiveRoom()
    {
        return $this->activeRoom;
    }

    public function setActiveRoom(BaseRoom $activeRoom): static
    {
        $this->activeRoom = $activeRoom;
        $this->activeRoom->load($this);
        return $this;
    }
}