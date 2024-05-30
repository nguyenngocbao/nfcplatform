<?php



return [
    'MAIN' => [
//        'NFC Device' => ['icon' => 'cpu','submenu' =>
//            [
//                ['name' => 'Device','url' => '/main/nfc'],
//                ['name' => 'Type','url' => '/main/nfc_type'],
//                ['name' => 'Chip','url' => '/main/nfc/chip'],
//            ],
//
//        ],
        'NFC Device' => ['icon' => 'cpu', 'url' => '/main/nfc'],
        'NFC Type' => ['icon' => 'cpu', 'url' => '/main/nfc_type'],
        'Platform' => ['icon' => 'package', 'url' => '/main/platform']
    ],
    'MENU-PLATFORM' => [
        'Device' => ['icon' => 'cpu', 'url' => '/menu/device'],
        'Store' => ['icon' => 'home', 'url' => '/menu/store' ],
        'Template' => ['icon' => 'file', 'url' => '/menu/template']
    ]




];
