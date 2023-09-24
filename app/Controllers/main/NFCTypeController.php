<?php

namespace App\Controllers\main;

use App\Controllers\CRUDController;

class NFCTypeController extends CRUDController
{
    const TABLE = 'nfc_type';
    const COLUMN = [
        'name' => self::STRING,
        'status' => self::INT];

    const VIEW = 'main/nfc_type';
    const COLUMN_TABLE = [
        ['get' => 'name'],
        ['get' => 'status']
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