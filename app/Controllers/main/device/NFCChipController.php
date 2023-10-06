<?php

namespace App\Controllers\main\device;

use App\Controllers\CRUDController;

class NFCChipController extends CRUDController
{
    const TABLE = 'nfc_chip';
    const COLUMN = [
        'name' => self::STRING,
        'status' => self::INT];

    const VIEW = 'main/nfc/chip';
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