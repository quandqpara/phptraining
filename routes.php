<?php
$controllers = array(
    'default' => ['index'],
    'admin' => ['index', 'auth', 'logout', 'home', 'searchAdmin', 'deleteAdmin',
        'createPageAdmin', 'createAdmin', 'editPageAdmin', 'editAdmin',
        'searchUser', 'searchPageUser', 'editPageUser', 'editUser'],
    'users' => ['index', 'auth', 'fbAuth', 'home' . 'logout'],
    'error' => ['error']
);

if (!array_key_exists($controller, $controllers) || !in_array($action, $controllers[$controller])) {
    $controller = 'error';
    $action = 'error';
}

include_once('controllers/' . $controller . '_controller.php');

$klass = str_replace('_', '', ucwords($controller, '_')) . 'Controller';
$controller = new $klass;
$controller->$action();