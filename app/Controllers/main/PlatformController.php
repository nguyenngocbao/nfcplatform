<?php

namespace App\Controllers\main;

use App\Controllers\CRUDController;

class PlatformController extends CRUDController
{
    const TABLE = 'platform';
    const COLUMN = [
        'name' => self::STRING,
        'description' => self::STRING,
        'platform_url' => self::STRING,
        'status' => self::INT];

    const VIEW = 'main/platform';
    const COLUMN_TABLE = [
        ['get' => 'name'],
        ['get' => 'platform_url'],
        ['get' => 'status'],
    ];
    protected function table()
    {
        return self::TABLE;
    }

    protected function column()
    {
        return self::COLUMN;
    }

    protected function _columnTable()
    {
        return self::COLUMN_TABLE;
    }

    protected function view()
    {
        return self::VIEW;
    }


    protected function param()
    {
        return [];

    }
}