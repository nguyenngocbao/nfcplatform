<?php

namespace App\Traits;

trait AccountTrait {
    
    public function data_salesman_by_manager(int $status = 1) {
        $current_username = session('account.username');
        $sql = "SELECT DISTINCT manager_username.username, location 
                FROM manager_username LEFT JOIN account ON manager_username.username = account.username 
                WHERE view_all_salesman = 0 AND account.status = {$status} AND (manager = '{$current_username}' OR manager_username.username = '{$current_username}') 
                ORDER BY location, manager_username.username";
        return db_fetch_all($sql);  
    }
    
    public function load_salesman_by_manager_list(int $status = 1) {
        return array_map(static fn ($v) => $v['username'], $this->data_salesman_by_manager($status));
    }

    public function load_source_by_session() {
        if (session('account.is_not_admin')) {
            $where = empty(session('account.source_list')) ? [] : ['id' => session('account.source_list')];
            return db_select('source', ['id', 'name'], $where);
        }
        return db_select('source', ['id', 'name']);
    }

    public function load_salesman_by_city_id(int $status = 1) {
        if ((!session('account.is_not_admin') || session_int('account.view_all_salesman')) && $city_id = post_int('city_id')) {
            $sql = "SELECT DISTINCT a.username, a.location 
                    FROM account a
                        JOIN store s ON a.username = s.salesman 
                        LEFT JOIN account_role ar ON a.username = ar.username 
                        INNER JOIN role r ON ar.role_id = r.id 
                    WHERE s.city_id = {$city_id} AND a.view_all_salesman = 0 AND a.status = {$status} ORDER BY a.location, a.username";                
            $salesman = db_fetch_all($sql);

            echo_json_success($this->_set_salesman($salesman));
        }

        echo_json_success($this->load_salesman($status));
    }

    public function salesman_by_implementation_unit(string $where, int $status = 1) {
        $sql = "SELECT DISTINCT account.username, location 
                FROM account 
                    LEFT JOIN account_role ON account.username = account_role.username 
                    INNER JOIN role ON account_role.role_id = role.id 
                WHERE view_all_salesman = 0 AND account.status = {$status} AND {$where} ORDER BY location, account.username";

        return db_fetch_all($sql);
    }

    private function _salesman_all(int $status = 1) {
        return (array) db_select('account', 
                ['username', 'location'], 
                ['view_all_salesman' => 0, 'status' => $status, 'ORDER' => db_raw('location, username')]);
    }
    
    public function load_salesman(int $status = 1, bool $telesale = false, bool $view_all_salesman = false) {
        $current_username = session('account.username');

        if (!session('account.is_not_admin')) {            
            $salesman = $this->_salesman_all($status);
        } elseif (session_int('account.view_all_salesman') || $view_all_salesman) {            
            if (empty(session('account.implementation_unit_list'))) {
                $salesman = $this->_salesman_all($status);                
            } else {
                $where     = array_map(static fn ($v) => "implementation_unit_list like '%{$v}%'", session('account.implementation_unit_list'));
                $where     = !empty($where) ? '(' . join(' OR ', $where) . ')' : '1=1';
                $telesale ? $where  .= ' AND account.telesale = 1' : 0;
                $salesman  = $this->salesman_by_implementation_unit($where, $status);
            }
        } elseif (in_array(session('account.title'), ['supervisor', 'manager'])) {
//            $salesman = db_select('manager_username', 
//                    ['[>]account' => ['manager_username.username' => 'username']], 
//                    ['manager_username.username', 'location'], 
//                    ['AND' => [
//                        'view_all_salesman' => 0,
//                        //'status' => 1,
//                        'OR' => ['manager' => $current_username, 'manager_username.username' => $current_username
//                        ]
//                    ], 'ORDER' => db_raw('location, manager_username.username')]);
            $sql = "SELECT DISTINCT manager_username.username, location 
                    FROM manager_username LEFT JOIN account ON manager_username.username = account.username 
                    WHERE view_all_salesman = 0 AND account.status = {$status} AND (manager = '{$current_username}' OR manager_username.username = '{$current_username}') 
                    ORDER BY location, manager_username.username";
            $salesman = db_fetch_all($sql);            
        } else {
            $salesman = [['username' => $current_username, 'location' => session('account.location')]];
        }

        return $this->_set_salesman($salesman);
    }

    public function load_salesman2(bool $telesale = false, bool $view_all_salesman = false, int $status = 1) {
        return array_map(static fn ($v) => $v['username'], $this->load_salesman($status, $telesale, $view_all_salesman));
    }

    private function _set_salesman($salesman) {
        return array_map(static fn ($v) =>
            [
                'username'      => $v['username'],
                'username_show' => ($v['location'] ? sprintf('%s - ', strtoupper($v['location'])) : '') . $v['username'],
            ], $salesman);
    }

}
