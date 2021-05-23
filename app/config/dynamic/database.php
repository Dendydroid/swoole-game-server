<?php

return [
    "connection" => [
        "dbname" => $_ENV["DATABASE_DB"] ?? "db",
        "user" => $_ENV["DATABASE_USER"] ?? "root",
        "password" => $_ENV["DATABASE_PASSWORD"] ?? "",
        "host" => $_ENV["DATABASE_HOST"] ?? "localhost",
        "driver" => $_ENV["DATABASE_DRIVER"] ?? "pdo_mysql",
    ],

    "entities" => [
        "path" => PROJECT_PATH . "/src/Database/Entity"
    ]
];
