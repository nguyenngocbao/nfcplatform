<?php

namespace App\Controllers;

abstract class CRUDController
{
    const INT = 'int';
    const STRING = 'String';
    const ARRAY = 'Array';
    const DATE = 'date';
    const IMAGE = 'image';

    protected abstract function view();
    protected abstract function param();
    protected abstract function table();
    protected abstract function column();

    public function indexAction(){
        $title = "";
        $icon = "";
        view($this->view(), array_merge(compact('icon', 'title'),$this->param()));
    }

    //LIST
    public function listAction(){
        echo_list(function ($page_index, $length, $start) {
            return $this->list($page_index, $length, $start);
        });
    }
    protected function list($page_index, $length, $start){
        $data = [];


        $query = $this->_query();
        $join = $query['join'];
        $column = $query['column'];
        $where = $query['where'];

        $total_record = db_select($this->table(),
            $join,
            $column, $where);

        if ($total_record = count($total_record)) {

            //$where['ORDER'] = db_raw("{$order_column} {$sort_type}");
            $where['LIMIT'] = [($page_index - 1) * $length, $length];


            $list = db_select($this->table(),
                $join,
                $column, $where);

            $data = $this->_data($list, $start);
        }


        return [$total_record, $data];

    }

    protected function _query(){
        $join = '*';
        $column = [];
        $where = $this->_where();
        return compact('join','column','where');
    }

    protected function _where(){
        return [];
    }

    protected function _data($list, $start){
        $data = [];
        $index = 1;

        foreach ($list as $v) {

            $function   = $this->_function($v);

            $row = [
                $start + ($index++)
            ];


            $row = array_merge($row,$this->_addColumToRow($v),[$function]);
            array_push($data, $row);
        }

        return $data;
    }
    protected function _function($v){

        $detail       =  html_edit('id', $v['id']);

        return $detail;
    }
    protected function _addColumToRow($data){
        $row = [];
        foreach ($this->_columnTable() as $c ){
            if ($c['get'] == 'status'){
                $content = getColValue($c['get'],$data) == 1 ? '<div class="badge badge-success">Active</div>': '<div class="badge badge-error">Inactive</div>';
            }else{
                $content = getColValue($c['get'],$data);
            }
            array_push($row, $content);
        }
        return $row;
    }
    protected abstract function _columnTable();

    //UPDATE

    public function updateAction(){
        $_d = $this->parseData();

        if ($id = post_int('id')) {
            $r = db()->update($this->table(), $_d, ['id' => $id]);
        } else {

            $r = db()->insert($this->table(), $_d);

        }

        if ($r->rowCount()) {
            echo_json_success();
        }
        echo_json_error();
    }

    protected function parseData(){
        $data = [];
        foreach ($this->column() as $key => $value) {
            switch ($value){
                case self::INT:
                    $data[$key] = post_int($key);
                    break;
                case self::STRING:
                case self::DATE:
                    $data[$key] = post($key);
                    break;
                case self::ARRAY:
                    $data[$key] = post_array($key);
                    break;
                case self::IMAGE:
                    $image = $this->upload_image('image');
                    $image && $data[$key] = $image;
                    break;
            }
        }
        return $data;
    }

    //DELETE

    public function deleteAction()
    {
        $id = post('id');
        if ($id) {
            $r = db()->delete($this->table(), ['id' => $id]);
            if ($r->rowCount()) {
                echo_json_success();
            }
        }
        echo_json_error();
    }
    //GET
    public function getAction()
    {
        $id = post('id');
        if ($id) {
            $data = db_get($this->table(),'*', ['id' => $id]);
            $data && echo_json_success($data);
        }
        echo_json_error();
    }







}