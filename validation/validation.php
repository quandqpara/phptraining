<?php
//validate input pre-access to database
function validateID($id): int
{
    $flag = 0;
    if (!is_numeric($id)) {
        $_SESSION['flash_message']['id']['invalid'] = getMessage('invalid_id');
        $flag += 1;
    }
    return $flag;
}

function validateEmail($email): int
{
    $flag = 0;
    if (empty($email)) {
        $_SESSION['old_data']['email'] = $email;
        $_SESSION['flash_message']['email']['empty'] = getMessage('email_empty');
        $flag += 1;
        return $flag;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['old_data']['email'] = $email;
        $_SESSION['flash_message']['email']['invalid'] = getMessage('invalid_email');
        $flag += 1;
        return $flag;
    }

    return $flag;
}

function validatePassword($password): int
{
    $flag = 0;
    if (empty($password)) {
        $_SESSION['flash_message']['password']['empty'] = getMessage('password_empty');
        $flag += 1;
    }

    //8+ letters
    //At least 1 Letter
    //At least 1 number
    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $password)) {
        $_SESSION['flash_message']['password']['invalid'] = getMessage('invalid_password');
        $flag += 1;
    }

    return $flag;
}

function renameUploadImage($imageName)
{
    return strstr($imageName, '@', true);
}

function getFileType($fileType)
{
    return ltrim(strstr($fileType, '/'), '/');
}

function validateAvatar($avatar, $email): int
{
    $flag = 0;
    showLog($_FILES, true);
    //check possible errors: empty, error, sizing, type
    if (isset($_FILES[$avatar])) {
        $_SESSION['flash_message']['avatar']['empty'] = getMessage('avatar_empty');
        $flag += 1;
    }

    if ($_FILES[$avatar]['error'] != 0) {
        $_SESSION['flash_message']['avatar']['error'] = getMessage('avatar_error');
        $flag += 1;
    }

    if ($_FILES[$avatar]['size'] < MBToByte(2)) {
        $_SESSION['flash_message']['avatar']['size'] = getMessage('avatar_over_size');
        $flag += 1;
    }

    if (!in_array($_FILES['type'], IMAGE_UPLOAD_FILE_TYPE)) {
        $_SESSION['flash_message']['avatar']['type'] = getMessage('invalid_avatar');
        $flag += 1;
    }

    //if no error was found, save the image to an actual folder.
    $targetDir = "uploads/avatar/";
    $fileType = getFileType($_FILES['avatar']['type']);
    $fileNameAfterSaved = renameUploadImage($email) . '-avatar' . $fileType;

    $targetFile = $targetDir . $fileNameAfterSaved;

    if ($flag === 0) {
        move_uploaded_file($_FILES['avatar']['tmp_name'], $targetFile);
    }
    return $flag;
}

function validateName($name): int
{
    $flag = 0;
    if (empty($name)) {
        $_SESSION['flash_message']['name']['empty'] = getMessage('name_empty');
        $flag += 1;
    }

    //Must only contain letters
    if (!preg_match('/^[a-zA-z]*$/', $name)) {
        $_SESSION['flash_message']['name']['invalid'] = getMessage('invalid_name');
        $flag += 1;
    }

    return $flag;
}

function validateVerifyPassword($pass1, $pass2): int
{
    $flag = 0;
    if ($pass1 !== $pass2 || empty($pass2)) {
        $_SESSION['flash_message']['verify_password']['invalid'] = getMessage('verify_password');
        $flag += 1;
    }
    return $flag;
}

function validateAdminRoles($role): int
{
    $flag = 0;
    if (empty($role)) {
        $_SESSION['flash_message']['role']['empty'] = getMessage('role_empty');
        $flag += 1;
        return $flag;
    }

    if ($role >= 2 || $role == 0) {
        $_SESSION['flash_message']['role']['invalid'] = getMessage('invalid_role');
        $flag += 1;
        return $flag;
    }
    return $flag;
}

function validateUsersStatus($status): int
{
    $flag = 0;
    if (empty($status)) {
        $_SESSION['flash_message']['status']['empty'] = getMessage('status_empty');
        $flag += 1;
        return $flag;
    }

    if ($status >= 2 || $status == 0) {
        $_SESSION['flash_message']['status']['invalid'] = getMessage('invalid_status');
        $flag += 1;
        return $flag;
    }
    return $flag;
}

function validateSubmitFormPostAndEmptyRequest($method, $request): int
{
    $flag = 0;
    if ($method !== 'POST') {
        $_SESSION['flash_message']['common']['failed'] = getMessage('common_error');
        $flag += 1;
    }

    if (empty($request)) {
        $_SESSION['flash_message']['common']['failed'] = getMessage('common_error');
        $flag += 1;
    }
    return $flag;
}

function validateSubmitFormGetAndEmptyRequest($method, $request): int
{
    $flag = 0;
    if ($method !== 'GET') {
        $_SESSION['flash_message']['common']['failed'] = getMessage('common_error');
        $flag += 1;
    }

    if (empty($request)) {
        $_SESSION['flash_message']['common']['failed'] = getMessage('common_error');
        $flag += 1;
    }
    return $flag;
}

//Function validate
function flagCheck($flag, $user, $location)
{
    if ($flag > 0) {
        header('Location: /' . $user . '/' . $location);
        exit;
    }
}

function validateAllInput()
{
    $flag = 0;
    foreach ($_REQUEST as $item) {
        switch ($item) {
            case 'avatar':
                $flag += validateAvatar($_REQUEST['avatar'], $_REQUEST['email']);
                break;
            case 'name':
                $flag += validateName($_REQUEST['name']);
                break;
            case 'email':
                $flag += validateEmail($_REQUEST['email']);
                break;
            case 'password':
                $flag += validatePassword($_REQUEST['password']);
                break;
            case 'verify':
                $flag += validateVerifyPassword($_REQUEST['password'], $_REQUEST['verify']);
                break;
            case 'role':
                $flag += validateAdminRoles($_REQUEST['role']);
                break;
        }
    }
    return $flag;
}

function validateAllUpdateInput(){
    $flag = 0;
    if (!empty($_REQUEST['password'])){
        foreach ($_REQUEST as $item) {
            switch ($item) {
                case 'avatar':
                    $flag += validateAvatar($_REQUEST['avatar'], $_REQUEST['email']);
                    break;
                case 'name':
                    $flag += validateName($_REQUEST['name']);
                    break;
                case 'email':
                    $flag += validateEmail($_REQUEST['email']);
                    break;
                case 'role':
                    $flag += validateAdminRoles($_REQUEST['role']);
                    break;
            }
        }
    }
    else if(empty($_REQUEST['password'])  && empty($_REQUEST['verify'])) {
        $flag += validateAllInput();
    }
    return $flag;
}

function validateAdminCreateForm($method)
{
    $error_flag = 0;

    $error_flag += validateSubmitFormPostAndEmptyRequest($method, $_REQUEST);

    flagCheck($error_flag, 'admin', 'createAdmin');

    $error_flag += validateAllInput();

    flagCheck($error_flag, 'admin', 'createAdmin');
    return true;

}

function validateLoginInput($method)
{
    $error_flag = 0;

    $error_flag += validateSubmitFormPostAndEmptyRequest($method, $_REQUEST);

    flagCheck($error_flag, 'admin', 'index');

    $error_flag += validateAllInput();

    flagCheck($error_flag, 'admin', 'index');
    return true;
}

function validateUpdateForm($method, $request, $id)
{
    $error_flag = 0;

    $error_flag += validateSubmitFormPostAndEmptyRequest($method, $request);

    flagCheck($error_flag, 'admin', 'editAdmin');

    $error_flag += validateID($id);

    flagCheck($error_flag, 'admin', 'editAdmin');

    $error_flag += validateAllUpdateInput();

    flagCheck($error_flag, 'admin', 'editAdmin');
    return true;
}

function validateDeleteForm($method, $id)
{
    $error_flag = 0;

    $error_flag += validateSubmitFormPostAndEmptyRequest($method, $_REQUEST);

    flagCheck($error_flag, 'admin', 'deleteAdmin');

    $error_flag += validateID($id);

    flagCheck($error_flag, 'admin', 'deleteAdmin');

    $error_flag += validateAllInput();

    flagCheck($error_flag, 'admin', 'deleteAdmin');
    return true;
}

function validateSearchForm($method)
{
    $error_flag = 0;

    //method should be get and $_GET should not be empty
    $error_flag += validateSubmitFormGetAndEmptyRequest($method, $_GET);

    flagCheck($error_flag, 'admin', 'home');

    //don't have to validate anything more than empty input
    if (!isset($_GET['email']) || !isset($_GET['name'])) {
        $_SESSION['flash_message']['email']['empty'] = getMessage('email_empty');
        $_SESSION['flash_message']['name']['empty'] = getMessage('name_empty');
        return false;
    }

    return true;
}

