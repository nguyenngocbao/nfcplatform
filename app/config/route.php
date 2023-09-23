<?php

/**
 * @author Bao
 */


use App\Controllers\IndexController;
use App\Controllers\PlatformForwardController;

use App\Controllers\main\NFCController;
use App\Controllers\main\PlatformController;


define('GET', 'GET');
define('POST', 'POST');

return [
    [GET, '', [IndexController::class, 'index']],
    [GET, '/', [IndexController::class, 'index']],
    [POST, '/login', [IndexController::class, 'login']],
    [GET, '/logout', [IndexController::class, 'logout']],

    [GET, '/nfcplatform/[*:uuid]', [PlatformForwardController::class, 'index']],

    [GET, '/main/platform', [PlatformController::class, 'index']],
    [POST, '/main/platform/delete', [PlatformController::class, 'delete']],
    [POST, '/main/platform/update', [PlatformController::class, 'update']],
    [POST, '/main/platform/get', [PlatformController::class, 'get']],
    [GET, '/main/platform/list', [PlatformController::class, 'list']],




    




    
];
