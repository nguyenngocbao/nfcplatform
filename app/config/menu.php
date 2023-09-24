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
        'Configuration' => ['icon' => 'settings', 'url' => '']
    ],
    'EVENT-PLATFORM' => [
        'Configuration' => ['icon' => 'settings', 'url' => '']
    ]




];
