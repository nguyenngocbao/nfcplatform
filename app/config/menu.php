<?php



return [
    'MAIN' => [
        'Nfc' => ['icon' => 'cpu','submenu' =>
            [
                ['name' => 'Configuration','url' => ''],
                ['name' => 'Management','url' => '/main/nfc'],
            ],

        ],
        'Platform' => ['icon' => 'package', 'url' => '/main/platform']
    ],
    'MENU-PLATFORM' => [
        'Configuration' => ['icon' => 'settings', 'url' => '']
    ],
    'EVENT-PLATFORM' => [
        'Configuration' => ['icon' => 'settings', 'url' => '']
    ]




];
