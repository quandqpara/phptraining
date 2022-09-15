<?php
//session_destroy();
require_once('Helper/common.php');
require_once('connection.php');
require_once('Helper/define_const.php');

session_start();

define('SERVER_DOMAIN', $_SERVER['SERVER_NAME']);
define('ROOT', dirname(__FILE__));

define('FB_GRAPH_VERSION', 'v14.0');
define('FB_GRAPH_DOMAIN', 'https://graph.facebook.com/');
define('FB_APP_STATE', 'eciphp');

$controller = 'default';
$action = 'index';

$url = ltrim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");

if (!empty($url)) {
    $path = explode("/", $url);
    if (count($path) >= 2) {
        $controller = $path[0];
        $action = $path[1];
    }
}

require_once('routes.php');
