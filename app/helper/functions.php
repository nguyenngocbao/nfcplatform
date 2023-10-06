<?php

function array_group_by($key, array $data) {
    $result = [];

    foreach ($data as $val) {
        if (array_key_exists($key, $val)) {
            $result[$val[$key]][] = $val;
        } else {
            $result[''][] = $val;
        }
    }

    return $result;
}

function upload_file(string $name, string $tmp_name, int $size, bool $image = true) {
    if ($size > 0) {
        $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $filename  = date('YmdHis') . '.' . uniqid() . '.' . $extension;
        $sub_dir   = date('Y-m-d');
        $file      = config('app.image.path_upload') . $sub_dir . '/' . $filename;
        $dir       = dirname($file);
//            is_dir($dir) or mkdir($dir, 0777, true);

        if (!is_dir($dir)) {
            mkdir($dir);
            chmod($dir, 0777);
        }
        if (move_uploaded_file($tmp_name, $file)) {
            if ($image) {
                try {
                    if (config('app.image.size_1_mb') < $size) {
                        $image = new Gumlet\ImageResize($file);
                        if ($size < config('app.image.size_2_mb')){
                            $image->scale(config('app.image.scale_1_percent'));
                        } elseif ($size >= config('app.image.size_2_mb')) {
                            $image->scale(config('app.image.scale_2_percent'));
                        }
                        $image->save($file);
                    }                    
                } catch (Exception $e) {
                    set_log($e);
                    return null;
                }
            }
            return $sub_dir . '/' . $filename;
        }
    }
    return null;
}

function now() {
    return date('Y-m-d H:i:s');
}

function today() {
    return date('Y-m-d');
}

function download_file_excel(string $filename, $writer) {
    $filename = substr(strrchr($filename, '\\'), 1);
    $filename = strtolower(preg_replace('/([A-Z])/', '_$1', lcfirst($filename)));
    $filename = str_replace('_controller', '', $filename);
    $filename = sprintf('%s_%s.xlsx', $filename, time());
    $filename = \XLSXWriter::sanitize_filename($filename);

    header('Content-disposition: attachment; filename="' . $filename . '"');
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Transfer-Encoding: binary');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    $writer->writeToStdOut();
    exit;
}

function date_show($date) {
    return str_replace(['0000-00-00 00:00:00', '0000-00-00'], '', $date);
}

function date_html($date) {
    $date = date_show($date);
    return $date ? '<span class="badge">' . $date . '</span>' : '';
}

function date_deadline($date, $check) {
    if ($date = date_show($date)) {
        $color = 'default';
        if ($check) {
            if ($date < today()) {
                $color = 'danger';
            } elseif ($date == today() || $date == date('Y-m-d', strtotime('+1 day'))) {
                $color = 'warning';
            }
        }

        return '<span class="label label-' . $color . '">' . $date . '</span>';
    }
    return '';
}

function allow_acl(string $access, string $controller = '') {
    if (session('account.is_not_admin')) {
        if (!is_contain($access, '@') && $controller) {
            $access = replace_namespace_controller($controller) . '@' . $access;
        }
        return in_array($access, permission());
    }
    return true;
}

function permission() {
    return session('account.permission') ?? [];
}

function array_unique_merge($array1, $array2) {
    return array_unique(array_merge($array1, $array2));
}

function html_active($is_active) {
    return $is_active ? '<span class="label label-success">Mở</span>' : '<span class="label label-danger">Tắt</span>';
}

function html_active_2($is_active) {
    return $is_active ? '<span class="label label-success">Có</span>' : '<span class="label label-danger">Không</span>';
}

function html_edit($key, $id) {
    return '<a class="btn btn-primary edit mr-2"  data-' . $key . '="' . $id . '" >Edit</i></a>';
}

function html_edit_link(string $href) {

    return '<a class="btn btn-info btn-xs margin-sm" href="' . $href . '" title="Cập nhật"><i class="fa fa-edit"></i></a>';
}

function html_edit_link_2(string $href, string $title, string $icon = 'fa-pencil') {
    return '<a class="btn btn-info bg-purple-opacity btn-xs margin-sm" href="' . $href . '" title="' . $title . '"><i class="fa ' . $icon . '"></i></a>';
}

function html_delete(...$param) {
    return '<a class="btn btn-danger btn-xs margin-sm delete" data-' . $param[0] . '="' . $param[1] . '" data-' . ($param[2] ?? '') . '="' . ($param[3] ?? '') . '" title="Xóa ?" data-toggle="tooltip"><i class="fa fa-times"></i></a>';
}

function html_load($key, $id) {
    return '<a class="btn btn-success btn-xs margin-sm load" data-' . $key . '="' . $id . '" title="Lấy thông tin" data-toggle="tooltip"><i class="fa fa-list"></i></a>';
}

function html_id($id) {
    return $id ? "<b>{$id}</b>" : '';
}

function html_link($href, $name, $blank = true) {
    return '<a href="' . $href . '" ' . ($blank ? 'target="_blank"' : '') . '><b>' . $name . '</b></a>';
}

function html_created_at($created_at, $color='') {
    $style = $color ? 'style="color:'.$color.'"' : '';
    return '<span class="created_at" '.$style.'>' . $created_at . '</span>';
}

function badge_html($id, string $text) {
    if ($id == 1) {
        $class = 'danger';
    } elseif ($id == 2) {
        $class = 'warning'; //default
    } elseif ($id == 3) {
        $class = 'success';
    } elseif ($id == 4) {
        $class = 'info';
    } else {
        return '';
    }

    return "<span class=\"badge badge-{$class}\">{$text}</span>";
}

function html_badge_salesman($salesman) {
    return $salesman ?: '<span class="badge badge-danger">Chưa gán</span>';
}

function option_unassign_salesman() {
    if (!session('account.is_not_admin') || session_int('account.view_all_salesman')) {
        return '<option value="1">Chưa gán Salesman</option>';
    }
    return '';
}

function html_checkbox_item($id) {
    return ' <label class="mt-checkbox mt-checkbox-outline" style="margin-left: 10px;"><input type="checkbox" class="item_id" value="' . $id . '"><span></span></label>';
}

function array_map_in($array) {
    return join_list(array_map(static fn ($v) => "'{$v}'", $array));
}

function title_controller(string $controller) {
    return get_config_title('controller', $controller);
}

function icon_controller(string $controller) {
    return get_config_title('icon', $controller);
}

function get_config_title($key, $controller) {
    $config     = config('title');
    $controller = replace_namespace_controller($controller);
    return $config[$key]["App\\Controllers\\{$controller}"] ?? '';
}

function title_action(string $action) {
    return config("title.{$action}", $action);
}

function time_human($s) {
    if ($s = (int) $s) {
        $s = (new Khill\Duration\Duration($s))->humanize();
        return str_replace(['d', 'h', 'm', 's'], ['ngày', 'giờ', 'phút', 'giây'], $s);
    }
    return '';
}

function phone_show($p) {
    $link = '<a href="callto:%s">%s</a>';

    if (is_contain($p, '|')) {
        $r = [];
        foreach (explode('|', $p) as $v) {
            $r[] = sprintf($link, $v, $v);
        }
        return join_show($r);
    }

    return $p ? sprintf($link, $p, $p) : '';
}

function insert_action_log(string $action, bool $second_save = false) {
    $data = ['username' => session('account.username'), 'action' => $action, 'created_at' => now()];
    $second_save && $data['second_save'] = time() - $_SERVER['REQUEST_TIME'];
    return db()->insert('action', $data);
}

function insert_store_log($store_id, array $log, $form_id = null) {
    unset($log['updated_at'], $log['updated_by'], $log['created_by'], $log['created_at'], $log['store_id']);
    foreach ($log as $k => $v) {
        if (!$v) {
            unset($log[$k]);
        }
    }    
    $data = ['username' => session('account.username') ?? 'cron', 'created_at' => now(), 'store_id' => $store_id, 'log' => json_encode($log), 'type' => $log['type'] ?? ''];
    $form_id && $data['form_id'] = $form_id;
    $data['second_save'] = time() - $_SERVER['REQUEST_TIME'];
    return db()->insert('store_log', $data);
}

function url(string $u = '') {
    return config('app.sub_domain') . '/' . trim($u, '/');
}

function curl_set(&$curl, $link) {
    curl_setopt($curl, CURLOPT_URL, $link);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    if (!config('app.debug')) {
        curl_setopt($curl, CURLOPT_PROXY, config('app.api_zalopay.proxy'));
    }

    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
}

function parse_time_api_zlp($v) {
    $d = date_parse($v);
    return date('Y-m-d H:i:s', mktime($d['hour'] + 7, $d['minute'], $d['second'], $d['month'], $d['day'], $d['year']));
}

function leaving_day($day) {
    if ($day) {
        $day = array_map(static fn ($x) => date_html($x), split($day));
        $day = join_show($day);
    }
    return $day;
}

function style_header_ex($widths = null) {
    $style = ['freeze_rows' => 1, 'freeze_columns' => 1, 'font-style' => 'bold', 'fill' => '#eee', 'halign' => 'center'];
    $widths && $style['widths'] = $widths;
    return $style;
}

function style_header_excel(int $col_start, int $col_end, int $width) {
    return style_header_ex(array_fill($col_start, $col_end, $width));
}

function name_by_id_db(array $a, $id) {
    foreach ($a as $v) {
        if ($v['id'] == $id) {
            return $v['name'];
        }
    }

    return ''; //$id
}

function name_by_id(array $a, $id) {
    foreach ($a as $k => $v) {
        if ($k == $id) {
            return $v;
        }
    }

    return ''; //$id
}

function get_image($data, $path_img = '') {
    $image = [];
    foreach (explode(config('app.image.split'), (string) $data) as $i) {
        if (!$i) {
            continue;
        }        
        $image[] = $path_img . $i;
    }
    return $image;
}

function http_domain() {
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') || (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443) ? 'https://' : 'http://';
    return $protocol . $_SERVER['HTTP_HOST'];
}

function link_image_upload() {
    return http_domain() . config('app.image.view_upload');
}

function set_start_end_search($start_search, $end_search) {
    return [$start_search.' 00:00:00', $end_search.' 23:59:59'];
}

function write_sheet_row(&$writer, $sheet, $row) {
    $writer->writeSheetRow($sheet, $row, ['border' => 'left,right,top,bottom', 'border-style' => 'thin']);
}

function id_name_show($id, $name) {
    return concat($id, ($id != $name ? $name : ''));
}

function join_list($list) {
    return join(',', (array) $list);
}

function join_list2($list) {
    return join(' , ', (array) $list);
}

function join_show($a) {
    return join(', ', (array) $a);
}

function filter_display(array $a) {
    return array_filter($a, static fn ($x) => (bool) $x);
}

function html_status($id, $text) {
    $class = 'warning';
    if ($id == 2) {
        $class = 'danger';
    } elseif ($id == 3) {
        $class = 'success';
    } elseif ($id == 4) {
        $class = 'default';
    }

    return "<span class=\"badge badge-{$class}\">{$text}</span>";
}

function parse_image_post($delete_image, $_image, $photo) {
    $picture_more = explode(config('app.image.split'), (string) $_image);
    if (!empty($delete_image) && !empty($picture_more)) {
        foreach ($delete_image as $p) {
            foreach ($picture_more as $k => $v) {
                if ($v == $p) {
                    unset($picture_more[$k]);
                }
            }
        }
    }
    $img_more = explode(config('app.image.split'), $photo);

    return join(config('app.image.split'), array_unique(array_merge($img_more, $picture_more)));
}

function human_price($price): string {
    $custom_number_format = static function ($n, $precision = 3) {
        $n = (int) $n;
        if ($n < 1000000) {
            // Anything less than a million
            $n_format = number_format($n);
        } elseif ($n < 1000000000) {
            // Anything less than a billion
            $n_format = number_format($n / 1000000, $precision) . 'M';
        } else {
            // At least a billion
            $n_format = number_format($n / 1000000000, $precision) . 'B';
        }

        return $n_format;
    };

    return strlen($price) > 7 ?
        str_replace(['M', 'B'], [' triệu', ' tỷ'], $custom_number_format($price)) :
        number_format_ex((int) $price, 0, ',', '.');
}

function percent_calculator($top, $bottom) {
    try {
        return $bottom ? sprintf('%.2f', $top / $bottom * 100) : 0;
    } catch (\Throwable $th) {}
    return 0;
}

function percent($numerator, $denominator) {
    return percent_calculator($numerator, $denominator) . '%';
}

function day_chart() {
    return is_mobile() ? 7 : 30;
}

function short_name_city($v) {
    return str_replace(['Hồ Chí Minh', 'Hà Nội'], ['HCM', 'HN'], $v);
}

function html_day_deadline($day) {
    if ($day < 0) {
        return " <span class=\"label label-danger\">Trễ {$day} ngày<span>";
    } elseif ($day > 0) {
        return " <span class=\"label label-success\">Sớm {$day} ngày<span>";
    }
}

function color_kpi($i) {
    if ($i<50) {
        return 'red';
    } elseif ($i<100) {
        return 'blue';
    } elseif ($i>100) {
        return 'green';
    }
}

function date_period($first, $last) {
    $last = new \DateTime($last);
    $last = $last->modify( '+1 day' ); 
    
    return new \DatePeriod(
            new \DateTime($first),
            new \DateInterval('P1D'),
            $last
    );
}

function icon_main_type($f) {
    return $f ? '<i class="fa ' . icon_controller(StoreMainTypeController::class) . '" title="Cửa hàng chính" style="color:#337ab7"></i> ' : '';
}

function icon_is_transaction($f) {
    return $f ? '<i class="fa fa-usd" title="Cửa hàng có Giao dịch" style="color:#c29d0b"></i> ' : '';
}

function icon_is_contract($f) {
    return $f ? '<i class="fa fa-check-square" title="'.config('title.action.pushContract').'" style="color:#21b384"></i> ' : '';
}

function get_store_name($v) {
    return icon_main_type($v['main_type'] == 1) . icon_is_transaction($v['is_transaction'] == 1)  . icon_is_contract($v['submit_contract'] == 1) . $v['name'];
}

function date_short($s) {
    return substr($s, 0, 10);
}

function show_yes_no($v) {
    return $v ? 'Có' : 'Không';
}

function show_on_off($v) {
    return $v ? 'Mở' : 'Tắt';
}

function show_list($v) {
    return str_replace(',', ' , ', $v);
}

function ably_publish(array $data) {
    $data['publish_by'] = current_username() ?? 'System';
    if (!config('app.debug')) {
        $_SERVER['https_proxy'] = config('app.api_zalopay.proxy');
    }

    static $client = null;
    if (is_null($client)) {
        $client  = new \Ably\AblyRest(config('app.ably_key.root'));
    }
    $channel = $client->channel('channels');
    $channel->publish('events', $data);
}

function message_publish(string $type, ?string $title, array $more=[]) {
    if (!$title) {
        return;
    }
    $data = ['type' => $type, 'message' => sprintf('<b style="color:#f89406">%s</b> %s', current_username() ?? 'System', str_replace("\'", '', $title))];
    $implementation_unit = implementation_unit_0();
    $implementation_unit && $data['implementation_unit'] = $implementation_unit;
    ably_publish(array_merge($data, $more));
}

function echo_json_success($d = null) {
             $r                = ['err' => 0, 'msg' => 'success'];
    !is_null($d) && $r['data'] = $d;
    echo_json($r);
}

function echo_json_error() {
    echo_json(['err' => 1, 'msg' => 'error']);
}

function concat(...$arr) {
    return join(' - ', $arr);
}

function split($s, string $sep = ',') {
    return explode($sep, $s);
}

function echo_list(callable $callback) {
    $length     = post_int('length');
    $length     = $length <= 0 ? 10 : $length;
    $start      = post_int('start');
    $start      = $start <= 0 ? 0 : $start;
    $page_index = $start / $length;
    $page_index = $page_index == 0 ? 1 : $page_index + 1;

    [$total_record, $data] = $callback($page_index, $length, $start);

    echo_json([
        'err'             => 0,
        'msg'             => '',
        'data'            => $data,
        'draw'            => post('draw'),
        'recordsTotal'    => $total_record,
        'recordsFiltered' => $total_record,
    ]);
}

function download_excel(string $class, callable $export) {
    init_execute_large();
    
    $writer = new \XLSXWriter();
    $export($writer, title_controller($class));

    download_file_excel($class, $writer);
}
function getColValue($col,$data){
    $result = '';
    $colNum = split($col,'-');
    $count = 0;
    foreach ($colNum as $item){
        $count++;
        if ($count == 1){
            $result = $data[$item];
        }else{
            $result .= ' - '.$data[$item];
        }
    }
    return $result;

}