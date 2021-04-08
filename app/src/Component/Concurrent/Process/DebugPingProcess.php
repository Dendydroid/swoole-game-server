<?php

namespace App\Component\Concurrent\Process;

use App\Component\Application\GameApplication;
use App\Component\Cache\Cache;
use App\Component\Concurrent\Listener\BaseListener;
use App\Component\Connection\ClientConnection;
use App\Tcp\Helper\Json;

class DebugPingProcess extends BaseProcess
{
    public function getMain(): callable
    {
        $server = GameApplication::app()->getServer();
        return function ($process) use ($server) {
            while (true) {
                sleep(1);
                if ($debugConnection = Cache::get("DEBUG_CONNECTION")) {

                    $processes = [];
                    $listeners = [];
                    $connections = [];
                    $container = [];

                    /** @var BaseProcess $pr */
                    foreach (GameApplication::processes() as $pr)
                    {
                        $processes[] = [
                            "name" => $pr->getProcessName()
                        ];
                    }

                    /** @var BaseListener $listener */
                    foreach (GameApplication::listeners() as $listener)
                    {
                        $listeners[] = [
                            "name" => $listener::class,
                            "listens" => $listener->listeningTo(),
                        ];
                    }

                    /** @var ClientConnection $connection */
                    foreach (GameApplication::getConnections() as $connection)
                    {
                        $connections[] = [
                            "ID" => $connection->getFd(),
                            "Created" => $connection->getCreated()
                        ];
                    }

                    foreach (GameApplication::getContainerData() as $key => $value)
                    {
                        $container[] = [
                            "key" => $key,
                            "value" => $value
                        ];
                    }

                    $cache = [];

                    foreach (Cache::keys() as $k)
                    {
                        $cache[] = [
                            "key" => $k,
                            "data" => Cache::get($k)
                        ];
                    }
                    GameApplication::app()->push($debugConnection, Json::encode([
                        "processes" => $processes,
                        "listeners" => $listeners,
                        "connections" => GameApplication::app()->getServer()->getClientList(0,100),
                        "container" => $container,
                        "cache" => $cache,
                    ]));
                }
            }
        };
    }
}