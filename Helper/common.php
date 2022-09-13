<?php

function setSessionAdmin($role){
    $_SESSION['admin']['role'] = (int)$role;
}

function getAdminID(){
    if($_SESSION['admin']){
        return $_SESSION['session_user']['id'];
    }
}

function getUserID(){
    if($_SESSION['user']){
        return $_SESSION['session_user']['id'];
    }
}

function setSessionUser(){
    $_SESSION['user'] = true;
}

function isAdmin(){
    if($_SESSION['admin'])
    {
        return true;
    } else {
        $_SESSION['flash_message']['permission']['no_permission_admin'] = getMessage('no_permission_admin');
        return false;
    }
}

function isSuperAdmin(){
    if($_SESSION['admin']['role'] == 2){
        return true;
    } else {
        $_SESSION['flash_message']['permission']['no_permission_super_admin'] = getMessage('no_permission_super_admin');
        return false;
    }
}

function isUser(){
    if($_SESSION['user'])
    {
        return true;
    }
    return false;
}



function isLoggedIn(){
    if (isset($_SESSION['admin'])) {
        header('Location: /admin/home');
        exit;
    }

    if (isset($_SESSION['user'])) {
        header('Location: /user/home');
        exit;
    }
}
function basicUserSetter($data){
    $_SESSION['session_user']['id'] = $data[0]['id'];
    $_SESSION['session_user']['name'] = $data[0]['name'];
    $_SESSION['session_user']['email'] = $data[0]['email'];
    $_SESSION['session_user']['avatar'] = $data[0]['avatar'];
}

//debug
function showLog($data, $continue = false)
{
    echo "<pre>";
    var_dump($data);
    echo "</pre>";

    if (!$continue) {
        die();
    }
}

//helper functions
function buildURL($url)
{
    return getServerProtocol() . SERVER_DOMAIN . '/' . $url;
}

function getServerProtocol()
{
    return (isset($_SERVER['HTTPS']) &&
        ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
        isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
        $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') ? 'https://' : 'http://';
}

function getMessage()
{
    $_errorMessages = '';
    //get all possible error messages
    if (empty($_errorMessages)) {
        $_errorMessages = require ROOT . '/config/error_message.php';
    }

    $arguments = func_get_args();
    $first = array_shift($arguments);

    //check if message exist
    if (!isset($_errorMessages[$first])) {
        return null;
    }

    $message = $_errorMessages[$first];

    //For multi-level of message
    while (true) {
        //If there is no more level in $argument, return the message
        if (!$arguments) {
            return $message;
        }

        //If next level of argument is null or $message is an array -> return null
        //Otherwise, the message is gotten.
        $key = array_shift($arguments);
        if (!is_array($message) || !isset($message[$key])) {
            return null;
        }
        $message = $message[$key];
    }
}

//function setFlashMessage($message, $grant = 'admin')
//{
//    if (empty($message)) {
//        return;
//    }
//
//    $_SESSION[$grant]['flash_message'] = $message;
//}
//
//function getFlashMessage($grant = 'admin')
//{
//    $flashMessages = $_SESSION[$grant]['flash_message'];
//
//    unset($_SESSION[$grant]['flash_message']);
//
//    return $flashMessages;
//}

function handleFlashMessage($message)
{
    $tempMessage = null;

    //check if there is any unread message
    if (isset($_SESSION['flash_message']) && empty($_SESSION['flash_message'])){
        unset($_SESSION['flash_message']);
        return $tempMessage;
    }

    //if ['flash_message'] . . . exist and has item
    //then check if ['flash_message']['item'].... contain message
    //only print first message from that ['item'][...] array
    //remove that section from 'flash_message'
    if(isset($_SESSION['flash_message']) && !empty($_SESSION['flash_message'])){
        if (!empty($_SESSION['flash_message'][$message])){
            $arrayMessage = $_SESSION['flash_message'][$message];
            $tempMessage = array_shift($arrayMessage);
        }
        unset($_SESSION['flash_message'][$message]);
    }

    if(count($_SESSION['flash_message']) == 0){
        unset($_SESSION['flash_message']);
    }
    return $tempMessage;
}

function retrieveOldFormData(){
    foreach ($_REQUEST as $item){
        if(array_search($item, $_REQUEST) !== 'password'){
            $_SESSION['old_data'][array_search($item, $_REQUEST)] = $item;
        }
    }
}

function handleOldData($targetInfo)
{
    $tempInput = null;

    if (isset($_SESSION['old_data'][$targetInfo])) {
        $tempInput = $_SESSION['old_data'][$targetInfo];
        unset($_SESSION['old_data'][$targetInfo]);
    }
    return $tempInput;
}

function oldData($field, $default = '')
{
    $data = handleOldData($field);
    return isset($data) ? $data : $default;
}

function MBToByte($size){
    return 1024 * 1024 * $size;
}

