<?php

namespace App\Controllers\menu;

use App\Controllers\CRUDController;

class TemplateController extends CRUDController
{
    const TABLE = 'template';
    const COLUMN = [
        'name' => self::STRING,
        'status' => self::INT];

    const VIEW = 'menu/template';
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