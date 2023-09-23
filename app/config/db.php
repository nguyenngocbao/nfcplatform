<?php

return [
    'options' => [
        'database_type' => 'mysql',
        'database_name' => env('db_name'),
        'charset'       => 'utf8mb4',
        'collation'     => 'utf8mb4_general_ci',
        'option'        => [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ],
    ],
    'write' => [
        [
            'server'   => env('db_server'),
            'port'     => env('db_port'),
            'username' => env('db_username'),
            'password' => env('db_password'),
        ]
    ],
    'read' => [
        [
            'server'   => env('db_server'),
            'port'     => env('db_port'),
            'username' => env('db_username'),
            'password' => env('db_password'),
        ],
        [
            'server'   => env('db_server'),
            'port'     => env('db_port'),
            'username' => env('db_username'),
            'password' => env('db_password'),
        ]
    ]
];

