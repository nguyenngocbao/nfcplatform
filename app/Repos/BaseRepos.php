<?php

namespace App\Repos;

abstract class BaseRepos
{
    protected abstract function table();

    public function update($data){

        if ($id = post_int('id')) {
            $r = db()->update($this->table(), $data, ['id' => $id]);
        } else {

            $r = db()->insert($this->table(), $data);

        }
        return $r->rowCount();

    }

    public function delete($id)
    {
        if ($id) {
            $r = db()->delete($this->table(), ['id' => $id]);
            return $r->rowCount();
        }
        return 0;

    }

    public function get($id)
    {
        $data = [];
        if ($id) {
            $data = db_get($this->table(),'*', ['id' => $id]);
        }
        return $data;

    }

}