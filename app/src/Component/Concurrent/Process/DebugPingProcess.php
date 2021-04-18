<?php

namespace App\Component\Concurrent\Process;

use App\Component\Application\GameApplication;
use App\Component\Cache\Cache;
use App\Component\Concurrent\Listener\BaseListener;
use App\Component\Connection\ClientConnection;
use App\Database\Entity\User;
use App\Tcp\Constant\CacheKeys;
use App\Tcp\Helper\Json;

class DebugPingProcess extends BaseProcess
{
    public function getMain(): callable
    {
        $app = GameApplication::app();
        return function ($process) use ($app) {
            while (true) {
                sleep(1);
                $connections = Cache::get(CacheKeys::CONNECTIONS_KEY);
                if (!empty($connections) && !is_bool($connections)) {
                    $adminEmail = "taras.galatsiuk@gmail.com";
                    $processes = [];
                    $listeners = [];
                    $container = [];

                    $em = GameApplication::database()->getEntityManger();
                    /** @var ClientConnection $connection */
                    foreach ($connections as $connection) {
                        /** @var User|null $user */
                        $user = $em->getRepository(User::class)->find($connection->getUserId());

                        if ($user && $user->getEmail() === $adminEmail) {
                            /** @var BaseProcess $pr */
                            foreach ($app::processes() as $pr) {
                                $processes[] = [
                                    "name" => $pr->getProcessName()
                                ];
                            }

                            /** @var BaseListener $listener */
                            foreach ($app::listeners() as $listener) {
                                $listeners[] = [
                                    "name" => $listener::class,
                                    "listens" => $listener->listeningTo(),
                                ];
                            }

                            foreach ($app::getContainerData() as $key => $value) {
                                $container[] = [
                                    "key" => $key,
                                    "value" => $value
                                ];
                            }

                            $cache = [];

                            foreach (Cache::keys() as $k) {
                                $cache[] = [
                                    "key" => $k,
                                    "data" => Cache::get($k)
                                ];
                            }

                            $connectionArray = [];

                            foreach ($connections as $c) {
                                $connectionArray[] = [
                                    "User ID" => $c->getUserId(),
                                    "FD" => $c->getFd(),
                                    "Active Room" => $c->getActiveRoom(),
                                    "Created" => $c->getCreated(),
                                ];
                            }

                            $app->push($connection->getFd(), Json::encode([
                                "processes" => $processes,
                                "listeners" => $listeners,
                                "connections" => $connectionArray,
                                "container" => $container,
                                "cache" => $cache,
                            ]));

                            break;
                        }
                    }
                }
            }
        };
    }
}