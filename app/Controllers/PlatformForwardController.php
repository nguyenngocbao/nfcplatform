<?php

namespace App\Controllers;

class PlatformForwardController
{
    public static function indexAction($uuid){
        $url = 'https://aton.live';
        header("Location: $url");
        exit();
    }

}