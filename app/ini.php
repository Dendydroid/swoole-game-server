<?php

define("PHP_ERROR_LOG_FILE", "./logs/php-errors.log");
define("PHP_STDOUT_FILE", "./logs/php-stdout.log");
define("PHP_STDERR_FILE", "./logs/php-stderr.log");
define("SERVICES_CONFIG_FILE", "./config/services.yaml");

define("PROJECT_PATH", __DIR__);

define("ERROR_ADDRESS_IN_USE", 98);
define("SERVER_RESTART_TIMEOUT", 1);
define("MAX_SERVER_REQUESTS", 10);

ini_set("log_errors", TRUE);

ini_set("error_log", PHP_ERROR_LOG_FILE);

fclose(STDIN);
fclose(STDOUT);
fclose(STDERR);
$STDIN = fopen('/dev/null', 'r');
$STDOUT = fopen(PHP_STDOUT_FILE, 'wb');
$STDERR = fopen(PHP_STDERR_FILE, 'wb');


