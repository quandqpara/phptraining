<?php
session_start();
//session_destroy();
require_once('Helper/common.php');
require_once('connection.php');
require_once('Helper/define_const.php');

define('SERVER_DOMAIN', $_SERVER['SERVER_NAME']);
define('ROOT', dirname(__FILE__));


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
