<?php
//session_destroy();
require_once('helper/common.php');
require_once('connection.php');
require_once('config/config.php');

session_start();

define('SERVER_DOMAIN', $_SERVER['SERVER_NAME']);
define('ROOT', dirname(__FILE__));

$controller = 'default';
$action = 'index';

$url = ltrim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");

if (!empty($url)) {
    $path = explode("/", $url);
    if (count($path) == 2) {
        $controller = $path[0];
        $action = $path[1];
    } elseif (count($path) >= 3) {
        $controller = strstr($url, '/' . $path[count($path) - 1], true);
        $action = $path[count($path) - 1];
    }
}
$_SESSION['page_title'] = $action;
require_once('routes.php');
