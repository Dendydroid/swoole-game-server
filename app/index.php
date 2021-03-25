<?php

require_once "vendor/autoload.php";

require_once "ini.php";

use App\Component\Application\GameApplication;

$app = new GameApplication(include_once("server.php"));

$app->loadServices(SERVICES_CONFIG_FILE);

GameApplication::set("app", $app);

$app->run();