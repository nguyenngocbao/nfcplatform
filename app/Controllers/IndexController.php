<?php

namespace App\Controllers;

use App\Traits\AccountTrait;

class IndexController
{

    use AccountTrait;

    private function _action()
    {
        if ($account = session('account')) {
            $this->_page_wellcome($account);
        }

        $mess = '';
        if (!empty(post())) {
            $account = db_get('account',
                ['username', 'fullname', 'role_id_list', 'title', 'view_all_salesman', 'assign_salesman', 'qc', 'telesale', 'location', 'image'],
                ['status' => 1, 'username' => post('username'), 'password' => md5(post('password'))]);
            if ($account) {
                $this->_process($account);
            } else {
                $mess = 'Đăng nhập không thành công!';
            }
        }
        page('login', ['title' => 'Đăng nhập', 'class' => 'login', 'mess' => $mess]);
    }

    private function _process($account)
    {
        $role_id_list = split($account['role_id_list']);
        $account['is_not_admin'] = !in_array(1, $role_id_list);
        $account['is_manager'] = !$account['is_not_admin'] || in_array($account['title'], ['manager']);

        [$permission, $implementation_unit_list, $city_list, $source_list] = $this->_parse_role_id_list($role_id_list);

        $account['permission'] = $permission;
        $account['implementation_unit_list'] = $implementation_unit_list;
        $account['image'] = $account['image'] ? config('app.image.view_upload') . $account['image'] : config('app.view_static') . '/static/layouts/layout/img/avatar.png';

        $array_map_int = static fn($list) => array_map(fn($v) => (int)$v, $list);
        $account['city_list'] = $array_map_int($city_list);
        $account['source_list'] = $array_map_int($source_list);

        $_SESSION['account'] = $account;

        //insert customer

        //insert_action_log('IndexController@login', true);
        if ($login_uri = session('login_uri')) {
            unset($_SESSION['login_uri']);
            redirect($login_uri);
        }
        redirect(url());
    }

    private function _parse_role_id_list($role_id_list)
    {
        $permission = $implementation_unit_list = $city_list = $source_list = [];

        foreach ($role_id_list as $role_id) {
            $role = db_get('role', ['permission', 'implementation_unit_list', 'city_list', 'source_list'], ['id' => $role_id, 'is_active' => 1]);
            if ($role) {
                //$_permission = $role['permission'] ? json_decode($role['permission'], true) : [];
                //$permission = array_unique_merge($permission, $_permission);

//                $_implementation_unit_list = $role['implementation_unit_list'] ? split($role['implementation_unit_list']) : [];
//                $implementation_unit_list = array_unique_merge($implementation_unit_list, $_implementation_unit_list);
//
//                $_city_list = $role['city_list'] ? split($role['city_list']) : [];
//                $city_list = array_unique_merge($city_list, $_city_list);
//
//                $_source_list = $role['source_list'] ? split($role['source_list']) : [];
//                $source_list = array_unique_merge($source_list, $_source_list);
            }
        }

        return [$permission, $implementation_unit_list, $city_list, $source_list];
    }

    public function indexAction()
    {
        $this->_action();
    }

    public function loginAction()
    {
        $this->_action();
    }

    public function logoutAction()
    {
        insert_action_log('IndexController@logout', true);
        session_unset();
        session_destroy();
        session_write_close();
        redirect(url());
    }

    private function _page_wellcome($account)
    {
        $title = 'Xin chào ' . $account['fullname'];

        view('wellcome', compact('title'));
    }


}
