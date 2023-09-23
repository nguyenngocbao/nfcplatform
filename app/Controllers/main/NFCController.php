<?php

namespace App\Controllers\main;

use App\Controllers\CRUDController;

class NFCController extends CRUDController
{

    const TABLE = 'nfc';
    const COLUMN = [
        'uuid' => self::STRING,
        'platform' => self::STRING,
        'owner' => self::STRING,
        'type' => self::INT];

    const VIEW = 'main/platform';
    const COLUMN_TABLE = [
        ['get' => 'name'],
        ['get' => 'description'],
        ['get' => 'description'],
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