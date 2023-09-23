<?php

/**
 * @static $db
 * @return \Medoo\Medoo
 */
function db(string $type = 'write') {
    static $db = [];

    $type = strtolower($type);
    if (isset($db[$type])) {
        return $db[$type];
    }
    
    if (in_array($type, ['write', 'read'])) {
        $index     = mt_rand(0, count(config("db.{$type}"))-1);
        $config    = array_merge(config('db.options'), config("db.{$type}.{$index}"));
        $db[$type] = new \Medoo\Medoo($config);
        
        return $db[$type];
    }
    
    die('db is not define');
}

function db_profile(callable $callback) {
    $start  = time();
    $result = $callback();
    $time   = time() - $start;
    if ($time >= config('app.profile_time_log')) {
        $sql = db('read')->last();
        $msg = "[{$time}s] - {$sql}";
        set_log($msg, 'alert', config('app.path_profile_db'));
    }
    
    return $result;
}

function db_fetch($sql) {
    return db_profile(static fn() => db('read')->query($sql)->fetch());
}

function db_fetch_all($sql) {
    return db_profile(static fn() => (array) db('read')->query($sql)->fetchAll());
}

function db_fetch_column($sql) {
    return db_profile(static fn() => db('read')->query($sql)->fetchColumn());
}

function db_select($table, $join, $columns = null, $where = null) {
    return db_profile(static fn() => db('read')->select($table, $join, $columns, $where));
}

function db_count($table, $join = null, $column = null, $where = null) {
    return db_profile(static fn() => db('read')->count($table, $join, $column, $where));
}

function db_get($table, $join = null, $columns = null, $where = null) {
    return db_profile(static fn() => db('read')->get($table, $join, $columns, $where));
}

function db_sum($table, $join, $column = null, $where = null) {
    return db_profile(static fn () => db('read')->sum($table, $join, $column, $where));
}

function db_raw(string $s) {
    return \Medoo\Medoo::raw("{$s}");
}

function count_table($table, $where=[]) {
    return number_format_ex(db_count($table, $where));
}