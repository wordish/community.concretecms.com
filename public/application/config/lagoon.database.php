<?php

return [
    'default-connection' => 'concrete',
    'connections' => [
        'concrete' => [
            'driver' => 'c5_pdo_mysql',
            'server' => getenv('MARIADB_HOST'),
            'database' => getenv('MARIADB_DATABASE'),
            'username' => getenv('MARIADB_USERNAME'),
            'password' => getenv('MARIADB_PASSWORD'),
            'port' => getenv('MARIADB_PORT'),
            'charset' => 'utf8mb4',
        ],
    ],
];
