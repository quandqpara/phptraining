<?php
session_start();
//session_destroy();
//die();

require_once('Helper/common.php');
require_once('connection.php');

define('SERVER_DOMAIN', $_SERVER['SERVER_NAME']);
define('ROOT', dirname(__FILE__));

$controller = 'default';
$action = 'index';

$url = ltrim($_SERVER['REQUEST_URI'],"/");

if (!empty($url))
{
    $path = explode("/", ltrim($url));
    if (count($path) >= 2) {
        $controller = $path[0];
        $action = $path[1];
    }
}

require_once('routes.php');
