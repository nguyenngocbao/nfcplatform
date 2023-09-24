<?php

namespace App\Controllers;

class PlatformForwardController
{
    public static function indexAction($uuid){
        $join = ['[>]platform' => ['nfc.platform' => 'id']];
        $column = ['platform.platform_url'];
        $where = ['nfc.uuid' => $uuid];
        $url = db_get('nfc',$join,$column,$where);
        $url = $url['platform_url'];
        header("Location: $url");
        exit();
    }

}