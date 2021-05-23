<?php

namespace App\Component\Server;

use App\Component\Application\GameApplication;
use App\Component\Request\Kernel;
use Swoole\Http\Request;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

class GameServer extends BaseServer
{
    public function init(): void
    {
        $app = GameApplication::app();

        if($app)
        {
            $this->server->on(
                "start",
                function (Server $server) {
                    echo date("d-M-Y H:i:s") . " - Swoole WebSocket Server is started at http://127.0.0.1:8443\n";
                }
            );

            $this->server->on(
                'open',
                function (Server $server, Request $request) use ($app) {
                    $app->connect($request);
                }
            );

            $this->server->on(
                'message',
                function (Server $server, Frame $frame) {
                    (new Kernel())->run($frame);
                }
            );

            $this->server->on(
                'close',
                function (Server $server, int $fd) use ($app) {
                    $app->disconnect($fd);
                }
            );
        }
    }

    public function run(): void
    {
        $this->server->start();
    }
}