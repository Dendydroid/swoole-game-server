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
        $this->server->on(
            "start",
            function (Server $server) {
                echo "Swoole WebSocket Server is started at http://127.0.0.1:8443\n";
            }
        );

        $this->server->on(
            'open',
            function (Server $server, Request $request) {
                GameApplication::connect($request);
//                $server->tick(
//                    1000,
//                    function () use ($server, $request) {
//                        $server->push($request->fd, json_encode(["This took so much time g..s ", time()]));
//                    }
//                );
            }
        );
//
//        [
//            new Route(
//                "/main/test",
//                MainController::class,
//                "main"
//            )
//        ]
        $this->server->on(
            'message',
            function (Server $server, Frame $frame) {
                (new Kernel())->run($frame);
//                $server->push($frame->fd, json_encode(["!!!!!", time()]));
            }
        );

        $this->server->on(
            'close',
            function (Server $server, int $fd) {
                GameApplication::disconnect($fd);
            }
        );
    }

    public function run()
    {
        $this->server->start();
    }
}