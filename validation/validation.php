<?php

function validateEmail($email = '')
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        return false;
    }
    return true;
}

function validatePassword($password = '')
{
    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $password)) {
        //8+ letters
        //At least 1 Letter
        //At least 1 number
        return false;
    }
    return true;
}

function flagCheck($flag){
    if ($flag > 0){
        header('Location: /admin/index');
        exit;
    }
}

function validateLoginInput($method, $request)
{
    $error_flag = 0;

    if ($method != 'POST') {
        $_SESSION['flash_message']['common']['failed'] = getMessage('common_error');
        $error_flag += 1;
    }

    if (empty($request)) {
        $_SESSION['flash_message']['common']['failed'] = getMessage('common_error');
        $error_flag += 1;
    }

    flagCheck($error_flag);

    if (empty($request['email'])) {
        $_SESSION['old_data']['email'] = $request['email'];
        $_SESSION['flash_message']['email']['empty'] = getMessage('email_empty');
        $error_flag += 1;
    }

    if (!validateEmail($request['email'])) {
        $_SESSION['old_data']['email'] = $request['email'];
        $_SESSION['flash_message']['email']['invalid'] = getMessage('invalid_email');
        $error_flag += 1;
    }

    if (empty($request['password'])) {
        $_SESSION['flash_message']['password']['empty'] = getMessage('password_empty');
        $error_flag += 1;
    }

    if (!validatePassword($request['password'])) {
        $_SESSION['flash_message']['password']['invalid'] = getMessage('invalid_password');
        $error_flag += 1;
    }

    flagCheck($error_flag);
    return true;
}

