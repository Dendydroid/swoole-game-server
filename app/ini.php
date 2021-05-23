<?php

const PHP_ERROR_LOG_FILE = "./logs/php-errors.log";
const PHP_STDOUT_FILE = "./logs/php-stdout.log";
const PHP_STDERR_FILE = "./logs/php-stderr.log";
const SERVICES_CONFIG_FILE = "./config/services.yaml";
const CONFIG_PATH = "/var/www/config/dynamic";
const CONFIG_DATA_PATH = "/var/www/config/dynamic";

const PROJECT_PATH = __DIR__;

const ERROR_ADDRESS_IN_USE = 98;
const SERVER_RESTART_TIMEOUT = 100000;
const MAX_SERVER_REQUESTS = 10;

ini_set("log_errors", true);

ini_set("error_log", PHP_ERROR_LOG_FILE);

fclose(STDIN);
fclose(STDOUT);
fclose(STDERR);

$STDIN = fopen('/dev/null', 'r');
$STDOUT = fopen(PHP_STDOUT_FILE, 'wb');
$STDERR = fopen(PHP_STDERR_FILE, 'wb');
