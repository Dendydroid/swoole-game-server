<?php

require_once "vendor/autoload.php";

require_once "ini.php";

use App\Component\Application\GameApplication;
use App\Component\Cache\Cache;
use Swoole\WebSocket\Server as SocketServer;
use App\Component\Concurrent\Command\HelpCommand;
use App\Component\Concurrent\Listener\TestListener;
use App\Component\Concurrent\Process\CLIProcess;
use App\Component\Concurrent\Process\DebugPingProcess;
use App\Component\Concurrent\Process\EventInvokerProcess;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(realpath(__DIR__ . '/.env'));

/* @var SocketServer $server */
$server = include_once("server.php");

$app = new GameApplication($server);
$app->loadServices(SERVICES_CONFIG_FILE);

GameApplication::set("app", $app);

$listeners = [
    new TestListener(),
];

GameApplication::set("listeners", $listeners);

$processes = [
    new EventInvokerProcess(),
    new DebugPingProcess(),
    new CLIProcess(),
];

foreach ($processes as $process) {
    $app->getServer()->addProcess($process);
}

GameApplication::set("processes", $processes);

$commands = [
    new HelpCommand(),
];

GameApplication::set("commands", $commands);

Cache::set("DEBUG_CONNECTIONS", []);

$app->run();