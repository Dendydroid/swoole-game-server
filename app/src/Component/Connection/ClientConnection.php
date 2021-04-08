<?php

namespace App\Component\Connection;

use Swoole\Http\Request;

class ClientConnection extends BaseConnection
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }
}