<?php

/**
 * @author TriNT <trint@vng.com.vn>
 */

require __DIR__ . '/../vendor/autoload.php';

Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../', '.env')->load();
define('APP_PATH', __DIR__ . '/../app/');

require APP_PATH . '/helper/init.php';
require APP_PATH . '/helper/functions.php';
require APP_PATH . '/helper/db.php';

App\Bootstrap::run();
