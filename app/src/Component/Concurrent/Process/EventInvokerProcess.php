<?php

namespace App\Component\Concurrent\Process;

use App\Component\Application\GameApplication;
use App\Component\Cache\Manager\EventManager;
use App\Component\Concurrent\Listener\BaseListener;

class EventInvokerProcess extends BaseProcess
{
    public function getMain(): callable
    {
//        $server = GameApplication::app()->getServer();
//        $listeners = GameApplication::app()->get("listeners");
        return function ($process) {
//            while (true) {
//                $events = EventManager::getInstance()->getList();
//                if (is_array($events) && !empty($events)) {
//                    foreach ($events as $event) {
//                        $eventObject = unserialize($event);
//                        if(!is_bool($eventObject))
//                        {
//                            /** @var BaseListener $listener */
//                            foreach ($listeners as $listener) {
//                                if (array_key_exists($eventObject::class, $listener->listeningTo())) {
//                                    $method = $listener->listeningTo()[$eventObject::class];
//                                    if ($listener->$method($eventObject)) {
//                                        EventManager::getInstance()->dispose($event);
//                                    }
//                                }
//                            }
//                        }
//                    }
//                }
//            }
        };
    }
}