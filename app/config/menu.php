<?php



return [
    'MAIN' => [
        'NFC Device' => ['icon' => 'cpu','submenu' =>
            [
                ['name' => 'Device','url' => '/main/nfc'],
                ['name' => 'Type','url' => '/main/nfc_type'],
            ],

        ],
        'Platform' => ['icon' => 'package', 'url' => '/main/platform']
    ],
    'MENU-PLATFORM' => [
        'Device' => ['icon' => 'cpu', 'url' => ''],
        'Store' => ['icon' => 'home', 'url' => ''],
        'Template' => ['icon' => 'file', 'url' => '']
    ],
    'CARDVISIT-PLATFORM' => [
        'Device' => ['icon' => 'cpu', 'url' => ''],
        'User' => ['icon' => 'settings', 'url' => ''],
        'Template' => ['icon' => 'file', 'url' => '']
    ]




];
