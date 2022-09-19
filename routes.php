<?php
$controllers = array(
    'default'               =>  ['index'],
    'error'                 =>  ['error'],

    'management/auth'       =>  ['index',       'logout', 'login'],

    'management/admin'      =>  ['home',        'editPageAdmin',    'createPageAdmin',  'searchPageUser',   'editPageUser',
                                'searchUser',   'editAdmin',        'createAdmin',      'searchAdmin',      'editUser',
                                'deleteUser',   'deleteAdmin'],
    'management/user'       =>  ['searchPageUser',  'editPageUser', 'searchUser',       'editUser',     'deleteUser'],

    'frontend/front'        =>  ['index', 'profile', 'auth', 'processingFacebookData', 'logout'],
);

if (!array_key_exists($controller, $controllers) || !in_array($action, $controllers[$controller])) {
    $controller = 'error';
    $action = 'error';
}

include_once('controllers/' . $controller . '_controller.php');

if(str_contains($controller, '/')){
    $controller = ltrim(strstr($controller, '/'),'/');
}

$klass = str_replace('_', '', ucwords($controller, '_')) . 'Controller';
$controller = new $klass;
$controller->$action();