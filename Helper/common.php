<?php

function showLog($data, $continue = false)
{
    echo "<pre>";
    var_dump($data);
    echo "</pre>";

    if (!$continue) {
        die();
    }
}

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

function handleFlashMessage($message)
{
    $tempMessage = null;

    if (isset($_SESSION['flash_message'][$message])){
        $tempMessage = $_SESSION['flash_message'][$message];
        unset($_SESSION['flash_message'][$message]);
    }

    return $tempMessage;
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