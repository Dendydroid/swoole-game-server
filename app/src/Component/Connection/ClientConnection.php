<?php

namespace App\Component\Connection;

use App\Component\Room\BaseRoom;
use Swoole\Http\Request;

class ClientConnection extends BaseConnection
{
    protected ?BaseRoom $activeRoom;
    protected int $userId;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->userId = 0;
        $this->activeRoom = null;
    }

    public function getActiveRoom(): ?BaseRoom
    {
        return $this->activeRoom;
    }

    public function setActiveRoom(BaseRoom $activeRoom): static
    {
        $this->activeRoom = $activeRoom;
        $this->activeRoom->load($this);
        return $this;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): static
    {
        $this->userId = $userId;
        return $this;
    }

}