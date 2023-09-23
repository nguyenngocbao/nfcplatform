<?php

namespace App\Traits;

trait CommonTrait {

    public function city() {
        $key = __TRAIT__ . '_' . __FUNCTION__;
        return cache_data($key, static fn() => db_select('city', '*', ['ORDER' => 'sort']), '3 month');
    }

    public function role_active() {
        return (array) db_select('role', ['id', 'name'], ['is_active' => 1, 'ORDER' => 'name']);
    }

    public function manager_active() {
        return (array) db_select('account', 'username', ['status' => 1, 'title' => ['supervisor', 'manager'], 'ORDER' => 'username']);
    }

    public function array_role_unique($role_id_list) {
        $city   = $this->city();
        $source = $this->source();
        $name   = $implementation_unit_list = $city_list = $source_list = [];
        foreach (split($role_id_list) as $role_id) {
            $role = db_get('role', ['name', 'implementation_unit_list', 'city_list', 'source_list'], ['id' => $role_id]);
            if ($role) {
                $name = array_unique_merge($name, (array) $role['name']);

                $_implementation_unit_list = $role['implementation_unit_list'] ? split($role['implementation_unit_list']) : [];
                $implementation_unit_list  = array_unique_merge($implementation_unit_list, $_implementation_unit_list);

                $_city_list = $role['city_list'] ? split($this->list_name($city, $role['city_list'])) : [];
                $city_list  = array_unique_merge($city_list, $_city_list);

                $_source_list = $role['source_list'] ? split($this->list_name($source, $role['source_list'])) : [];
                $source_list  = array_unique_merge($source_list, $_source_list);
            }
        }

        return [
            join_show($name),
            join_show($implementation_unit_list),
            join_show($city_list),
            join_show($source_list),
        ];
    }

    public function list_name($data, $list_id, $by_db = true) {
        $list = [];
        foreach (split($list_id) as $id) {
            if (!$id) {
                continue;
            }

            $_name = $by_db ? name_by_id_db($data, $id) : name_by_id($data, $id);
            $_name && $list[] = $_name;
        }

        return join_show($list);
    }

    public function upload_image($name) {
        $r    = '';
        $file = files($name);
        is_array($file) && $r = (string) upload_file($file['name'], $file['tmp_name'], $file['size']);
        return $r;
    }

}
