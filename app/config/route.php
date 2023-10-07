<?php

/**
 * @author Bao
 */


use App\Controllers\IndexController;
use App\Controllers\PlatformForwardController;

use App\Controllers\main\NFCController;
use App\Controllers\main\NFCTypeController;
use App\Controllers\main\PlatformController;

use App\Controllers\menu\TemplateController;
use App\Controllers\menu\StoreController;
use App\Controllers\menu\DeviceController;


define('GET', 'GET');
define('POST', 'POST');

return [
    [GET, '', [IndexController::class, 'index']],
    [GET, '/', [IndexController::class, 'index']],
    [POST, '/login', [IndexController::class, 'login']],
    [GET, '/logout', [IndexController::class, 'logout']],

    [GET, '/nfcplatform/[*:uuid]', [PlatformForwardController::class, 'index']],
    [GET, '/profile', [\App\Controllers\ProfileController::class, 'index']],

    [GET, '/main/platform', [PlatformController::class, 'index']],
    [POST, '/main/platform/delete', [PlatformController::class, 'delete']],
    [POST, '/main/platform/update', [PlatformController::class, 'update']],
    [POST, '/main/platform/get', [PlatformController::class, 'get']],
    [GET, '/main/platform/list', [PlatformController::class, 'list']],

    [GET, '/main/nfc', [NFCController::class, 'index']],
    [POST, '/main/nfc/delete', [NFCController::class, 'delete']],
    [POST, '/main/nfc/update', [NFCController::class, 'update']],
    [POST, '/main/nfc/get', [NFCController::class, 'get']],
    [GET, '/main/nfc/list', [NFCController::class, 'list']],

    [GET, '/main/nfc_type', [NFCTypeController::class, 'index']],
    [POST, '/main/nfc_type/delete', [NFCTypeController::class, 'delete']],
    [POST, '/main/nfc_type/update', [NFCTypeController::class, 'update']],
    [POST, '/main/nfc_type/get', [NFCTypeController::class, 'get']],
    [GET, '/main/nfc_type/list', [NFCTypeController::class, 'list']],

    [GET, '/menu/store', [StoreController::class, 'index']],
    [POST, '/menu/store/delete', [StoreController::class, 'delete']],
    [POST, '/menu/store/update', [StoreController::class, 'update']],
    [POST, '/menu/store/update-template', [StoreController::class, 'updateTemplate']],
    [POST, '/menu/store/get', [StoreController::class, 'get']],
    [GET, '/menu/store/list', [StoreController::class, 'list']],
    [POST, '/menu/store/get-district', [StoreController::class, 'dictrict']],
    [POST, '/menu/store/get-ward', [StoreController::class, 'ward']],

    [GET, '/menu/device', [DeviceController::class, 'index']],
    [POST, '/menu/device/delete', [DeviceController::class, 'delete']],
    [POST, '/menu/device/update', [DeviceController::class, 'update']],
    [POST, '/menu/device/get', [DeviceController::class, 'get']],
    [GET, '/menu/device/list', [DeviceController::class, 'list']],

    [GET, '/menu/template', [TemplateController::class, 'index']],
    [POST, '/menu/template/delete', [TemplateController::class, 'delete']],
    [POST, '/menu/template/update', [TemplateController::class, 'update']],
    [POST, '/menu/template/get', [TemplateController::class, 'get']],
    [GET, '/menu/template/list', [TemplateController::class, 'list']],




    




    
];
