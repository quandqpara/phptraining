<?php
session_start();

$_SESSION['error'] = [
    'invalid_email' => 'Your email is invalid. PLease try again.',
    'invalid_password' => 'Your password is incorrect. PLease try again.',
    'invalid_account_status' => 'You has been banned. Contact for support.',
];


require_once('Helper/common.php');
require_once('connection.php');

define('SERVER_DOMAIN', $_SERVER['SERVER_NAME']);

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
