<?php

require_once "vendor/autoload.php";

require_once "ini.php";

use App\Component\Application\GameApplication;
use App\Component\Concurrent\Command\DoctrineGenerate;
use App\Component\Concurrent\Command\RegisterUserCommand;
use App\Component\Concurrent\Process\CacheCleanerProcess;
use App\Component\Config\Config;
use App\Database\Database;
use Swoole\WebSocket\Server as SocketServer;
use App\Component\Concurrent\Listener\TestListener;
use App\Component\Concurrent\Process\CLIProcess;
use App\Component\Concurrent\Process\DebugPingProcess;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(realpath(__DIR__ . '/.env'));

define("DEBUG_MODE", $_ENV['DEBUG_MODE'] ?? false);

/* @var SocketServer $server */
$server = include("server.php");

$app = new GameApplication($server);

$app->loadServices(SERVICES_CONFIG_FILE);

$config = Config::getInstance()->setConfigFolder(CONFIG_PATH)->load();

$database = Database::getInstance();

$app->appData()->setKey("config", $config);

$app->set("database", $database);

GameApplication::$app = $app;

$listeners = [
    new TestListener(),
];

$app->appData()->setKey("listeners", $listeners);

$processes = [
//    new EventInvokerProcess(),
    new DebugPingProcess(),
    new CLIProcess(),
    new CacheCleanerProcess(),
];

$processClasses = [];
foreach ($processes as $process) {
    $processClasses[] = $process::class;
    $app->getServer()->addProcess($process);
}

$app->appData()->setKey("processes", $processClasses);

$commands = [
    new DoctrineGenerate(),
    new RegisterUserCommand(),
];

$app->appData()->setKey("commands", $commands);

$app->run();
