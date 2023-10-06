<?php

return [
    'options' => [
        'database_type' => 'mysql',
        'database_name' => 'menuplatform',
        'charset'       => 'utf8mb4',
        'collation'     => 'utf8mb4_general_ci',
        'option'        => [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ],
    ],
    'write' => [
        [
            'server'   => '127.0.0.1',
            'port'     => '3306',
            'username' => 'admin',
            'password' => '123456',
        ]
    ],
    'read' => [
        [
            'server'   => '127.0.0.1',
            'port'     => '3306',
            'username' => 'admin',
            'password' => '123456',
        ]
    ]
];

