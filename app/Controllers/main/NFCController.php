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
        'type' => self::INT,
        'status' => self::INT];

    const VIEW = 'main/nfc';
    const COLUMN_TABLE = [
        ['get' => 'uuid'],
        ['get' => 'platform_name'],
        ['get' => 'owner'],
        ['get' => 'nfc_type_name'],
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
        $platforms = db_select('platform','*');
        $nfcTypes = db_select('nfc_type','*');
        return compact('platforms','nfcTypes');
    }

    //LIST
    //overide sql query /list
    protected function _query(){
        $join = ['[>]platform' => ['nfc.platform' => 'id'], '[>]nfc_type' => ['nfc.type' => 'id']];
        $column = array_map(function ($key){
            return $this->table().'.'.$key;
        },array_keys($this->column()));
        $column = array_merge([$this->table().'.id',
            'platform.name(platform_name)','nfc_type.name(nfc_type_name)'],
            $column );
        $where = $this->_where();
        return compact('join','column','where');
    }

    protected function _addColumToRow($data){
        $row = [];
        foreach ($this->_columnTable() as $c ){
            switch ($c['get']){
                case 'status':
                    $content = getColValue($c['get'],$data) == 1 ? '<div class="badge badge-success">Active</div>': '<div class="badge badge-error">Inactive</div>';
                    break;
                case 'owner':
                    $content = 'Admin';
                    break;
                default:
                    $content = getColValue($c['get'],$data);
                    break;
            }
            array_push($row, $content);
        }
        return $row;
    }


}