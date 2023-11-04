<?php

/**
 * @author Bao
 */
return [
    'debug' => env('debug'),
    'sub_domain'  => env('sub_domain'),
    'view_static' => env('view_static'),
    'version' => '1.0.0',
    'path_log'  => realpath(__DIR__ . '/../../') . '/logs/' . date('Y-m-d') . '.log',
    'path_view' => realpath(__DIR__ . '/../../') . '/app/views/',
    
    'image' => [
        'path_upload'     => realpath(__DIR__ . '/../../') . '/public_html/static/upload/',
        'view_upload'     => env('view_upload'),
        'size_1_mb'       => 2097152,
        'size_2_mb'       => 5242880,
        'scale_1_percent' => 70,
        'scale_2_percent' => 50,
        'split'           => '|||',
    ],
    'profile_time_log' => 26,                                               //
    'path_profile_db'  => realpath(__DIR__ . '/../../') . '/logs/db.pro',
    'url' => [
        'gateway' => 'http://localhost:8005'
    ]
    
];
