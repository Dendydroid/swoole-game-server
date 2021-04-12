<?php

namespace App\Component\Concurrent\Process;

use App\Component\Application\GameApplication;
use App\Component\Cache\Manager\EventManager;
use App\Component\Concurrent\Listener\BaseListener;

class EventInvokerProcess extends BaseProcess
{
    public function getMain(): callable
    {
        $server = GameApplication::app()->getServer();
        $listeners = GameApplication::get("listeners");
        return function ($process) use ($server, $listeners) {
            while (true) {
                $events = EventManager::service()->getList();
                if (is_array($events) && !empty($events)) {
                    foreach ($events as $event) {
                        $eventObject = unserialize($event);
                        /** @var BaseListener $listener */
                        foreach ($listeners as $listener) {
                            if (in_array($eventObject::class, array_keys($listener->listeningTo()))) {
                                $method = $listener->listeningTo()[$eventObject::class];
                                if ($listener->$method($eventObject)) {
                                    EventManager::service()->dispose($event);
                                }
                            }
                        }
                    }
                }
            }
        };
    }
}