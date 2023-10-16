<?php

namespace App\Controllers\menu;

use App\Controllers\CRUDController;

class StoreController extends CRUDController
{
    const TABLE = 'store';
    const COLUMN = [
        'name' => self::STRING,
        'phone' => self::STRING,
        'address' => self::STRING,
        'ward_id' => self::STRING,
        'district_id' => self::STRING,
        'template_id' => self::STRING,
        'city_id' => self::STRING,
        'store_type' => self::STRING,
        'wifi_pass' => self::STRING,
        'email' => self::STRING,
        'status' => self::INT];

    const VIEW = 'menu/store';
    const COLUMN_TABLE = [
        ['get' => 'name'],
        ['get' => 'address'],
        ['get' => 'store_type'],
        ['get' => 'phone'],
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
        $city = $this->loadCity();
        $store_type_list = db_select('store_type',"*");
        $template_list = db_select('template',"*");
        return compact("city","store_type_list",'template_list');

    }

    protected function _query(){
        $join = ['[>]ward' => ['store.ward_id' => 'id'],
            '[>]district' => ['store.district_id' => 'id'],
            '[>]city' => ['store.city_id' => 'id'],
            '[>]store_type' => ['store.store_type' => 'id']];
        $column = array_map(function ($key){
            return $this->table().'.'.$key;
        },array_keys($this->column()));
        $column = array_merge([$this->table().'.id',
            'ward.name(ward)','district.name(district)','city.name(city)','store_type.name(store_type_name)'],
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
                case 'address':
                    $content = $data['address'].", ".$data['ward'].", ".$data['district'].", ".$data['city'];
                    break;
                case 'store_type':
                    $content = $data['store_type_name'];
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

        //$detail       =  html_edit('id', $v['id']);
        $template = '<button class="btn btn-primary template mr-2 "  data-' . id . '="' . $v['id'] . '" >Template</i></button>';
        $menu = '<button class="btn btn-info menu mr-2 "  data-' . id . '="' . $v['id'] . '" >Menu</i></button>';

        return $template.$menu;
    }

    public function dictrictAction(){
        $data = [];
        if ($id = post_int('id')){
            $data = $this->loadDistrictByCity($id);
        }
        echo_json_success($data);

    }

    public function wardAction(){
        $data = [];
        if ($id = post_int('id')){
            $data = $this->loadWardByDistrict($id);
        }
        echo_json_success($data);

    }

    private function loadCity(){
        return db_select('city',"*");
    }

    private function loadDistrictByCity($city_id){
        return db_select('district',"*",['city_id' => $city_id]);
    }
    private function loadWardByDistrict($district_id){
        return db_select('ward',"*",['district_id' => $district_id]);
    }

    //UPDATE TEMPLATE
    public function updateTemplateAction(){
        //
        if (post_int('id') && $template_id = post_int('template_id')){
            $r = db()->update($this->table(), ['template_id' => $template_id], ['id' => post_int('id')]);
            echo_json(post());
        }
        echo_json_error();

    }


}