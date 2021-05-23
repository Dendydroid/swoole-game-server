<?php

namespace App\Component\Concurrent\Process;

use App\Component\Application\GameApplication;
use App\Component\Cache\Cache;
use App\Component\Concurrent\Listener\BaseListener;
use App\Component\Connection\ClientConnection;
use App\Component\Enum\UserRoleEnum;
use App\Component\Service\SharedApplicationData;
use App\Component\Service\SharedConnections;
use App\Component\Service\SharedContainer;
use App\Component\Service\SharedServer;
use App\Database\Database;
use App\Database\Entity\User;
use App\Tcp\Helper\Json;

class DebugPingProcess extends BaseProcess
{
    public function getMain(): callable
    {
        return function ($process) {
            while (true) {
                sleep(1);
                $connections = new SharedConnections();
                $connectionList = $connections->getConnections();
                if (!empty($connectionList) && !is_bool($connectionList)) {
                    $processes = [];
                    $listeners = [];
                    $container = [];

                    /** @var ClientConnection $connection */
                    foreach ($connectionList as $connection) {
                        $database = Database::getInstance();
                        $em = $database->getEntityManger();
                        /** @var User|null $user */
                        $user = $em->getRepository(User::class)->find($connection->getUserId());
                        if ($user && $user->getRole() === UserRoleEnum::ROLE_ADMIN) {

                            $appData = new SharedApplicationData();

                            /** @var BaseProcess $pr */
                            foreach ($appData->getData()->processes as $pr) {
                                $processes[] = [
                                    "name" => $pr->getProcessName()
                                ];
                            }

                            /** @var BaseListener $listener */
                            foreach ($appData->getData()->listeners as $listener) {
                                $listeners[] = [
                                    "name" => $listener::class,
                                    "listens" => $listener->listeningTo(),
                                ];
                            }

                            $sharedContainer = new SharedContainer();
                            $container = $sharedContainer->getContainer();

                            if ($container !== null) {
                                foreach ($container->all() as $key => $value) {
                                    $container[] = [
                                        "key" => $key,
                                        "value" => $value
                                    ];
                                }
                            }


                            $cache = [];

                            foreach (Cache::keys() as $k) {
                                $cache[] = [
                                    "key" => $k,
                                    "data" => Cache::get($k)
                                ];
                            }

                            $connectionArray = [];

                            foreach ($connectionList as $c) {
                                $connectionArray[] = [
                                    "User ID" => $c->getUserId(),
                                    "FD" => $c->getFd(),
                                    "Active Room" => $c->getActiveRoom(),
                                    "Created" => $c->getCreated(),
                                ];
                            }

                            $server = (new SharedServer())->getServer();

                            if ($server) {
                                $server->getServer()->push($connection->getFd(), Json::encode([
                                    "processes" => $processes,
                                    "listeners" => $listeners,
                                    "connections" => $connectionArray,
                                    "container" => $container,
                                    "cache" => $cache,
                                ]));
                            }

                            break;
                        }
                    }
                }
            }

        };
    }
}