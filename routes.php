<?php
$controllers = array(
    'default' => ['index'],
    'admin' => ['index', 'auth', 'home', 'logout',
                'createPageAdmin','createAdmin', 'searchAdmin', 'editPageAdmin', 'editAdmin',
                'createUserPage', 'createUser', 'searchUser', 'editPageUser', 'editUser'],
    'users' => ['index', 'auth', 'fbAuth', 'home'. 'logout'],
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