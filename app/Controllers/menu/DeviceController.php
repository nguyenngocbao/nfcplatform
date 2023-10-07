<?php

namespace App\Controllers\menu;

use App\Controllers\CRUDController;

class DeviceController extends CRUDController
{
    const TABLE = 'device_store';
    const COLUMN = [
        'device_uuid' => self::STRING,
        'store_id' => self::INT,
        'status' => self::INT];

    const VIEW = 'menu/device';
    const COLUMN_TABLE = [
        ['get' => 'device_uuid'],
        ['get' => 'store_name'],
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

    //LIST
    //overide sql query /list
    protected function _query(){
        $join = ['[>]store' => ['device_store.store_id' => 'id']];
        $column = array_map(function ($key){
            return $this->table().'.'.$key;
        },array_keys($this->column()));
        $column = array_merge([
            'store.name(store_name)'],
            $column );
        $where = $this->_where();
        return compact('join','column','where');
    }

    protected function _function($v){

        $text = $v['status'] == 1 ? 'Disable' : 'Enable';

        $disable_btn =  '<button class="btn btn-primary  disable mr-2"  data-id'. '="' . $v['id'] . '" > '.$text.'</i></button>';

        return $disable_btn;
    }

}