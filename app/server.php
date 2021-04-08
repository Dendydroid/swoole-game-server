<?php

require_once "vendor/autoload.php";

use App\Component\Exception\ExceptionFormatter;
use Codedungeon\PHPCliColors\Color;
use Swoole\WebSocket\Server as SocketServer;

$server = null;

while (is_null($server)) {
    try {
        $server = new SocketServer("0.0.0.0", 8443);
    } catch (Throwable $exception) {
        if ($exception->getCode() === ERROR_ADDRESS_IN_USE) {
            sleep(SERVER_RESTART_TIMEOUT);
            continue;
        }
        \App\Cli\Reporter::reportMessage(ExceptionFormatter::toLogString($exception), Color::RED) and die();
    }
}

$server->set(
    [
        // logging
        'log_level' => 1,
        'log_file' => '/logs/php-swoole-errors.log',
        'log_rotation' => SWOOLE_LOG_ROTATION_DAILY | SWOOLE_LOG_ROTATION_SINGLE,
        'log_date_format' => true, // or "day %d of %B in the year %Y. Time: %I:%S %p",
        'log_date_with_microseconds' => false,

        // ssl
        'ssl_cert_file' => __DIR__ . '/config/certificate.crt',
        'ssl_key_file' => __DIR__ . '/config/privateKey.key',
        'ssl_ciphers' => 'ALL:!ADH:!EXPORT56:RC4+RSA:+HIGH:+MEDIUM:+LOW:+SSLv2:+EXP',
        'ssl_protocols' => 0, // added from v4.5.4
        'ssl_verify_peer' => false,
        'ssl_sni_certs' => [
            "cs.php.net" => [
                'ssl_cert_file' => __DIR__ . "/config/sni_server_cs_cert.pem",
                'ssl_key_file' => __DIR__ . "/config/sni_server_cs_key.pem"
            ],
            "uk.php.net" => [
                'ssl_cert_file' => __DIR__ . "/config/sni_server_uk_cert.pem",
                'ssl_key_file' => __DIR__ . "/config/sni_server_uk_key.pem"
            ],
            "us.php.net" => [
                'ssl_cert_file' => __DIR__ . "/config/sni_server_us_cert.pem",
                'ssl_key_file' => __DIR__ . "/config/sni_server_us_key.pem",
            ],
        ],

        // compression
        'http_compression' => true,
        'http_compression_level' => 3, // 1 - 9
        'http_gzip_level' => 1,
        'compression_min_length' => 20,

        // websocket
        'websocket_compression' => true,
        'open_websocket_close_frame' => false,
        'open_websocket_ping_frame' => false, // added from v4.5.4
        'open_websocket_pong_frame' => false, // added from v4.5.4

    ]
);

return $server;