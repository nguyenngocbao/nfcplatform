<?php

namespace App\Controllers\main;

use App\Controllers\CRUDController;
use Ramsey\Uuid\Uuid;

class NFCController extends CRUDController
{

    const TABLE = 'nfc';
    const COLUMN = [
        'platform' => self::STRING,
        'owner' => self::STRING,
        'type' => self::INT,
        'status' => self::INT];

    const VIEW = 'main/nfc';
    const COLUMN_TABLE = [
        ['get' => 'id'],
        ['get' => 'serial'],
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
        $column = array_merge([$this->table().'.id',$this->table().'.count',
            'platform.name(platform_name)','nfc_type.name(nfc_type_name)'],
            $column );
        $where = $this->_where();
        return compact('join','column','where');
    }

    protected function _addColumToRow($data){
        $row = [];
        foreach ($this->_columnTable() as $index => $c ){
            switch ($c['get']){
                case 'status':
                    $content = getColValue($c['get'],$data) == 1 ? '<div class="badge badge-success">Active</div>': '<div class="badge badge-error">Inactive</div>';
                    break;
                case 'owner':
                    $content = 'Admin';
                    break;
                case 'serial':
                    $content = str_pad($data['count'], 6, '0', STR_PAD_LEFT);
                    break;
                default:
                    $content = getColValue($c['get'],$data);
                    break;
            }
            array_push($row, $content);
        }
        return $row;
    }

    protected function _function($v){

        $detail       =  html_edit('id', $v['id']);

        $url = config('app.url.gateway').'/'.$v['id'];

        $view_url =  '<a class="btn btn-green view-url mr-2"  data-url'. '="' . $url . '" >View URL</i></a>';

        return $detail.$view_url;
    }

    protected function _where(){
        return ["ORDER" => ["nfc.count" => "ASC"] ];
    }

    //UPDATE
    public function updateAction(){
        $_d = $this->parseData();
        $data = [];


        if ($id = post_int('id')) {
            $r = db()->update($this->table(), $_d, ['id' => $id]);
        } else {
            $_d['id'] = Uuid::uuid4() -> toString();
            $r = db() ->insert($this->table(), $_d);
            $id = $_d['id'];
        }

        if ($r->rowCount()) {
            $data = ['id' => $id, 'url' => config('app.url.gateway').'/'.$id ];
            echo_json_success($data);
        }
        echo_json_error();
    }


}