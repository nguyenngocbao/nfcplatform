<?php

/**
 * @author TriNT <trint@vng.com.vn>
 */
if (config('app.debug')) {
    ini_set('display_errors', 'on');
    ini_set('display_startup_errors', 'on');
}

error_reporting(E_ERROR | E_PARSE);
set_error_handler('handle_error');
set_exception_handler('handle_exception');
register_shutdown_function('shutdown');
date_default_timezone_set('Asia/Ho_Chi_Minh');

magic_quotes();

function set_log(string $msg, string $type = 'log', string $file = '') {
    !$file && $file = config('app.path_log');
    $date_format    = 'H:i:s';
    $message_format = '%date% | %type% | %message%';

    $dir = dirname($file);
    if (!is_dir($dir)) {
        mkdir($dir);
        chmod($dir, 0777);
    }
    if (in_array(strtolower($type), ['log', 'debug', 'error', 'warning', 'critical', 'custom', 'alert', 'notice', 'info', 'emergency', 'special', 'custom'])) {
        $msg = str_replace(
                        ['%date%', '%type%', '%message%'],
                        [date($date_format, time()), strtoupper($type), $msg],
                        $message_format
                ) . PHP_EOL;

        return file_put_contents($file, $msg, FILE_APPEND | LOCK_EX);
    }
    return false;
}

function handle_error(int $errno, string $errstr, string $errfile, int $errline) {
    if ($errno & error_reporting()) {
        throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
    }
}

function handle_exception($e) {
    switch ($e->getCode()) {
        case E_NOTICE:
        case E_USER_NOTICE:
            $type = 'notice';
            break;
        case E_WARNING:
        case E_CORE_WARNING:
        case E_COMPILE_WARNING:
        case E_USER_WARNING:
            $type = 'warning';
            break;
        default:
            $type = 'error';
    }
    set_log($e, $type);
    if (config('app.debug')) {
        $whoops = new Whoops\Run;
        if (is_cli()) {
            $whoops->pushHandler(new Whoops\Handler\PlainTextHandler);
        } elseif (is_ajax()) {
            $whoops->pushHandler(new Whoops\Handler\JsonResponseHandler);
        } else {
            $whoops->pushHandler(new Whoops\Handler\PrettyPageHandler);
        }

        $whoops->handleException($e);
        $whoops->register();
    } else {
        $title = $e->getMessage();
        page_error(503, $title);
    }
}

function page_error(int $code, string $title) {
    if (is_cli()) {
        die($title);
    } elseif (is_ajax()) {
        http_response_code($code);
        echo_json(['err' => 1, 'msg' => $title]);
    }
    http_response_code($code);
    page($code, ['title' => $title, 'class' => '']);
}

function page_403() {
    $title = 'Quyền truy cập';
    if (is_cli()) {
        die($title);
    } elseif (is_ajax()) {
        http_response_code(403);
        echo_json(['err' => 1, 'msg' => $title]);
    }
    http_response_code(403);
    view('access-denied', compact('title'));
}

function shutdown() {
    $isFatal = static fn ($errno) => in_array($errno, [E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR, E_COMPILE_WARNING]);
    if (!is_null($error = error_get_last()) && $isFatal($error['type'])) {
        throw new ErrorException($error['message'], $error['type'], 0, $error['file'], $error['line']);
    }
}

function _render(string $template, array $data = [], callable $callback = null) {
    extract((array) $data, EXTR_SKIP);
    $__filepath__ = config('app.path_view') . $template . '.phtml';
    if (!is_file($__filepath__)) {
        die("View file does not exist：{$__filepath__}");
    }
    $callback ? ob_start($callback) : ob_start();
    include $__filepath__;

    return ob_get_clean();
}

function page(string $name, $data = null) {
    $_data           = is_callable($data) ? $data() : $data;
    $data            = (array) $_data;
    $data['content'] = _render($name, $data);

    echo_exit(_render('_layouts', $data, 'ob_gzhandler'));
}

function block(string $name, $data = []) {
    $data = is_callable($data) ? $data() : $data;
    return _render("_blocks/{$name}", $data);
}

function replace_namespace_controller(string $text) {
    return str_replace('App\\Controllers\\', '', $text);
}

function view(string $template, $data = []) {
    $page       = _render($template, $data);
    $get_action = static function ($link) {
        foreach (config('route') as $r) {
            if ("/{$link}" == $r[1]) {
                return replace_namespace_controller(join('@', $r[2]));
            }
        }
        return false;
    };


    page('_index', [
        'title'    => $data['title'],
        'class'    => 'page-header-fixed page-sidebar-closed-hide-logo page-content-white page-md page-sidebar-closed',
        'page'     => $page,
        'customer' => $data['is_customer_view'] ? block('customer-view') : '',
        'header'   => block('header'),
        'templates'=> block('templates'),
        'siderbar' => block('siderbar', ['get_action' => $get_action, 'menu' => config('menu')])
    ]);
}

function echo_json($data) {
    header('Content-Type: application/json; charset=utf-8');
    echo_exit(json_encode($data));
}

function echo_exit($s) {
    echo $s;
    if (isset($_SERVER['id_action_log'])) {
        db()->update('action', ['second_save' => time() - $_SERVER['REQUEST_TIME']], ['id' => $_SERVER['id_action_log']]);
    }
    exit();
}

function config(string $name, $default = null) {
    static $config = [];

    $is_contain_dot = is_contain($name, '.');
    if ($is_contain_dot) {
        $_     = explode('.', $name);
        $_name = $_[0];
        unset($_[0]);
        $name = join('.', $_);
    } else {
        $_name = $name;
    }

    if (!isset($config[$_name])) {
        $path = APP_PATH . "config/{$_name}.php";
        if (file_exists($path)) {
            $config[$_name] = include $path;
        }
    }

    if (isset($config[$_name])) {
        if ($is_contain_dot) {
            return _get_data($config[$_name], $name, $default);
        }
        return $config[$_name];
    }

    return $default;
}

function _get_data($data, string $name, $default = null) {
    foreach ($name ? explode('.', $name) : [] as $key) {
        if (!isset($data[$key])) {
            return $default;
        }
        $data = $data[$key];
    }

    return $data;
}

function magic_quotes() {
    $_GET     = add_magic_quotes($_GET);
    $_POST    = add_magic_quotes($_POST);
    $_COOKIE  = add_magic_quotes($_COOKIE);
    $_SERVER  = add_magic_quotes($_SERVER);
    $_REQUEST = array_merge($_GET, $_POST);
}

function add_magic_quotes($array) {
    foreach ((array) $array as $k => $v) {
        if (is_array($v)) {
            $array[$k] = add_magic_quotes($v);
        } elseif (is_string($v)) {
            $array[$k] = addslashes($v);
        }
    }

    return $array;
}

function request(string $name = null, $default = null) {
    return $name ? _get_data($_REQUEST, $name, $default) : $_REQUEST;
}

function request_int(string $name) {
    return (int) request($name);
}

function request_trim(string $name) {
    return trim(request($name));
}

function request_array(string $name) {
    return (array) request($name);
}

function post(string $name = null, $default = null) {
    return $name ? _get_data($_POST, $name, $default) : $_POST;
}

function post_int(string $name) {
    return (int) post($name);
}

function post_array(string $name) {
    return (array) post($name);
}

function post_trim(string $name) {
    return trim(post($name));
}

function get(string $name = null, $default = null) {
    return $name ? _get_data($_GET, $name, $default) : $_GET;
}

function get_int(string $name) {
    return (int) get($name);
}

function session(string $name = null, $default = null) {
    return $name ? _get_data($_SESSION, $name, $default) : $_SESSION;
}

function session_int(string $name) {
    return (int) session($name);
}

function files(string $name = null, $default = null) {
    return $name ? _get_data($_FILES, $name, $default) : $_FILES;
}

function is_cli() {
    return php_sapi_name() == 'cli';
}

/**
 * @static $cache
 * @return \DivineOmega\DOFileCache\DOFileCache
 */
function cache() {
    static $cache = null;

    if (is_null($cache)) {
        $cache = new DivineOmega\DOFileCache\DOFileCache();
    }

    return $cache;
}

function cache_data(string $key, callable $clouse, string $life_time) {
    $data = cache()->get($key);
    if (!$data) {
        $data = $clouse();
        cache()->set($key, $data, strtotime('+ '.$life_time));
    }    

    return $data;
}

function redirect(string $link) {
    header("Location: {$link}");
    exit();
}

function is_ajax() {
    return 'xmlhttprequest' == strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '');
}

function init_execute_large() {
    // set_time_limit(0);
    ini_set('max_execution_time', 0);
    ini_set('memory_limit', '1500M');
}

function is_end_with($haystack, $needle) {
    return strrpos($haystack, $needle) === (strlen($haystack) - strlen($needle));
}

function is_start_with($haystack, $needle) {
    return strpos($haystack, $needle) === 0;
}

function is_contain($haystack, $needle) {
    return stripos($haystack, $needle) !== false;
}

function slug(?string $string, array $replace = [], string $delimiter = '-') {
    if (!$string) {
        return '';
    }

    // Save the old locale and set the new locale to UTF-8
    $oldLocale = setlocale(LC_ALL, '0');
    setlocale(LC_ALL, 'en_US.UTF-8');

    // Better to replace given $replace array as index => value
    // Example $replace['ı' => 'i', 'İ' => 'i']
    if (!empty($replace) && is_array($replace)) {
        $string = str_replace(array_keys($replace), array_values($replace), $string);
    }

    $string = Transliterator::create('Any-Latin; Latin-ASCII')->transliterate(
        mb_convert_encoding(htmlspecialchars_decode($string), 'UTF-8', 'auto')
    );

    // Revert back to the old locale
    /** @noinspection CaseInsensitiveStringFunctionsMissUseInspection */
    if (stripos($oldLocale, '=') > 0) {
        $loc = [];
        parse_str(str_replace(';', '&', $oldLocale), $loc);
        $oldLocale = array_values($loc);
    }

    setlocale(LC_ALL, $oldLocale);

    // replace non letter or non digits by -
    $string = preg_replace('#[^\pL\d]+#u', '-', $string);

    // Trim trailing -
    $string = trim($string, '-');

    $clean = strtolower(preg_replace('~[^-\w]+~', '', $string));
    $clean = preg_replace('#[\/_|+ -]+#', $delimiter, $clean);

    return trim($clean, $delimiter);
}

function is_mobile(): bool {
    return (bool) preg_match('/(blackberry|configuration\/cldc|hp |hp-|htc |htc_|htc-|iemobile|kindle|midp|mmp|motorola|mobile|nokia|opera mini|opera |Googlebot-Mobile|YahooSeeker\/M1A1-R2D2|android|iphone|ipod|mobi|palm|palmos|pocket|portalmmm|ppc;|smartphone|sonyericsson|sqh|spv|symbian|treo|up.browser|up.link|vodafone|windows ce|xda |xda_)/i', $_SERVER['HTTP_USER_AGENT']);
}

/**
 * @param string $key
 * @param null $default
 * @return array|bool|string|null
 */
function env(string $key, $default = null) {
    $value = getenv($key);

    if ($value === false) {
        return $default;
    }

    switch (strtolower($value)) {
        case 'true':
        case '(true)':
            return true;
        case 'false':
        case '(false)':
            return false;
        case 'empty':
        case '(empty)':
            return '';
        case 'null':
        case '(null)':
            return null;
    }

    if (($valueLength = strlen($value)) > 1 && $value[0] === '"' && $value[$valueLength - 1] === '"') {
        return substr($value, 1, -1);
    }

    return $value;
}

function number_format_ex(?int $number): string {
    return number_format($number, 0, ',', '.');
}

function cut_ex(?string $string, int $length, string $end = '...') : string {
    $string = strip_tags(trim($string));
    
    if (strlen($string) > $length) {
        $stringCut = substr($string, 0, $length);
        $string    = substr($stringCut, 0, strrpos($stringCut, ' ')) . $end;
    }

    return trim($string);
}

function send_email($email, $subject, $content) {
    try {
        $aParam               = array();
        
        $aParam['from']       = config('app.email.from');
        $aParam['to']         = $email;
        $aParam['subject']    = $subject;
        $aParam['body']       = $content;
        $aParam['clientIp']   = '127.0.0.1';
        $aParam['wsAccount']  = config('app.email.wsAccount');
        $aParam['wsPassword'] = config('app.email.wsPassword');

        $sRawDataSign = '';
        foreach ($aParam as $v) {
            $sRawDataSign .= $v;
        }
        $sign               = md5($sRawDataSign . config('app.email.dataSign'));
        $aParam['dataSign'] = $sign;

        $oClient   = new SoapClient(config('app.email.url'));
        $arrResult = $oClient->__call('SendEmail', array($aParam));
        
        return $arrResult->SendEmailResult->anyType[0];
    } catch (Exception $e) {
        set_log($e);
    }
    return 0;
}