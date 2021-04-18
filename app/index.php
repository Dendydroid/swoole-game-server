<?php

require_once "vendor/autoload.php";

require_once "ini.php";

use App\Component\Application\GameApplication;
use App\Component\Cache\Cache;
use App\Component\Concurrent\Command\DoctrineGenerate;
use App\Component\Concurrent\Command\RegisterUserCommand;
use App\Component\Concurrent\Process\CacheCleanerProcess;
use App\Component\Config\Config;
use App\Database\Database;
use App\Tcp\Constant\CacheKeys;
use Swoole\WebSocket\Server as SocketServer;
use App\Component\Concurrent\Command\HelpCommand;
use App\Component\Concurrent\Listener\TestListener;
use App\Component\Concurrent\Process\CLIProcess;
use App\Component\Concurrent\Process\DebugPingProcess;
use App\Component\Concurrent\Process\EventInvokerProcess;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(realpath(__DIR__ . '/.env'));

define("DEBUG_MODE", $_ENV['DEBUG_MODE'] ?? false);

/* @var SocketServer $server */
$server = include_once("server.php");

$app = new GameApplication($server);

$app->loadServices(SERVICES_CONFIG_FILE);

$config = Config::getInstance()->setConfigFolder(CONFIG_PATH)->load();

$database = Database::getInstance();

GameApplication::set("config", $config);

GameApplication::set("database", $database);

GameApplication::set("app", $app);

$listeners = [
    new TestListener(),
];

GameApplication::set("listeners", $listeners);

$processes = [
//    new EventInvokerProcess(),
    new DebugPingProcess(),
    new CLIProcess(),
    new CacheCleanerProcess(),
];

foreach ($processes as $process) {
    $app->getServer()->addProcess($process);
}

GameApplication::set("processes", $processes);

$commands = [
    new DoctrineGenerate(),
    new RegisterUserCommand(),
];

GameApplication::set("commands", $commands);

$app->run();