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