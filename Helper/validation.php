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
    if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/", $password)) {
        return false;
    }
    return true;
}
