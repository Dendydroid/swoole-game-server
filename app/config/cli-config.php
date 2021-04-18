<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Database\Database;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Symfony\Component\Dotenv\Dotenv;

define("PROJECT_PATH", "/var/www");
define("CONFIG_PATH", "/var/www/config/dynamic");

$dotenv = new Dotenv();
$dotenv->load(realpath(PROJECT_PATH . '/.env'));

define("DEBUG_MODE", $_ENV['DEBUG_MODE'] ?? false);

$database = Database::getInstance();

$entityManager = $database->getEntityManger();

$entityManager->getConnection()->connect();

return ConsoleRunner::createHelperSet($entityManager);