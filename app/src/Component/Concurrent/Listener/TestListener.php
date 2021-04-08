<?php

namespace App\Component\Concurrent\Listener;

use App\Component\Application\GameApplication;
use App\Component\Concurrent\Event\TestEvent;
use App\Tcp\Helper\Json;

class TestListener extends BaseListener
{
    public function listeningTo(): array
    {
        return [
            TestEvent::class => "onTestEvent",
        ];
    }

    public function onTestEvent(TestEvent $event): bool
    {
        sleep(5);
        GameApplication::app()->push($event->getFd(), Json::encode([
            "message" => "Dude, that shit literally just fucking worked..."
        ]));
        return true;
    }
}