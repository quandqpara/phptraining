<?php
//-----------------------------COMPONENT VALIDATION----------------------------------
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

function validateAvatar($avatar): int
{
    $flag = 0;
    //check possible errors: empty, error, sizing, type
    if (!isset($_FILES[$avatar])) {
        $_SESSION['flash_message']['avatar']['empty'] = getMessage('avatar_empty');
        $flag += 1;
    };
    if ($_FILES[$avatar]['error'] != 0) {
        $_SESSION['flash_message']['avatar']['error'] = getMessage('avatar_error');
        $flag += 1;
    }

    if ($_FILES[$avatar]['size'] > MBToByte(2)) {
        $_SESSION['flash_message']['avatar']['size'] = getMessage('avatar_over_size');
        $flag += 1;
    }

    if (!in_array($_FILES['avatar']['type'], IMAGE_UPLOAD_FILE_TYPE)) {
        $_SESSION['flash_message']['avatar']['type'] = getMessage('invalid_avatar');
        $flag += 1;
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

//----------------------------------HELPER-------------------------------------------
function flagCheck($flag, $user, $location)
{
    if ($flag > 0) {
        retrieveOldFormData();
        header('Location: /' . $user . '/' . $location);
        exit;
    }
}

//----------------------------------ADMIN VALIDATION----------------------------------
function validateLoginInput($method)
{
    $error_flag = 0;

    $error_flag += validateSubmitFormPostAndEmptyRequest($method, $_REQUEST);

    flagCheck($error_flag, 'admin', 'index');

    $error_flag += validateAllInput();

    flagCheck($error_flag, 'admin', 'index');
    return true;
}

//for Create
function validateAllInput()
{
    $flag = 0;
    foreach ($_REQUEST as $item) {
        switch ($item) {
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

//for Update
function validateAllUpdateInput()
{
    $flag = 0;
    if (!empty($_REQUEST['password'])) {
        foreach ($_REQUEST as $item) {
            switch ($item) {
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
    } else if (empty($_REQUEST['password']) && empty($_REQUEST['verify'])) {
        $flag += validateAllInput();
    }
    return $flag;
}

function validateAdminCreateForm($method, $avatarFlag)
{
    $error_flag = 0;

    $error_flag += validateSubmitFormPostAndEmptyRequest($method, $_POST);

    if (is_numeric($avatarFlag)) {
        $error_flag += $avatarFlag;
    }

    $error_flag += validateAllInput();

    //if flag check failed -> redirect
    flagCheck($error_flag, 'admin', 'createPageAdmin');
    //otherwise return true
    return true;

}

function validateUpdateForm($method, $request, $id)
{
    $error_flag = 0;

    $error_flag += validateSubmitFormPostAndEmptyRequest($method, $request);

    flagCheck($error_flag, 'admin', 'editAdmin?id=' . $id);

    $error_flag += validateID($id);

    flagCheck($error_flag, 'admin', 'editAdmin?id=' . $id);

    $error_flag += validateAllUpdateInput();

    flagCheck($error_flag, 'admin', 'editAdmin?id=' . $id);
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

//----------------------------------USER VALIDATION----------------------------------
function validateAllUpdateInputForUser()
{
    $flag = 0;

    if (!isset($_REQUEST['password'])) {
        foreach ($_REQUEST as $item) {
            switch ($item) {
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
    } else if (isset($_REQUEST['password'])) {
        foreach ($_REQUEST as $item) {
            switch ($item) {
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
    }


    return $flag;
}

function validateSearchFormForUser($method)
{
    $error_flag = 0;

    //method should be get and $_GET should not be empty
    $error_flag += validateSubmitFormGetAndEmptyRequest($method, $_GET);

    flagCheck($error_flag, 'admin', 'searchPageUser');

    //don't have to validate anything more than empty input
    if (!isset($_GET['email']) || !isset($_GET['name'])) {
        $_SESSION['flash_message']['email']['empty'] = getMessage('email_empty');
        $_SESSION['flash_message']['name']['empty'] = getMessage('name_empty');
        return false;
    }

    return true;
}

function validateUpdateFormForUser($method, $request, $id)
{
    $error_flag = 0;

    $error_flag += validateSubmitFormPostAndEmptyRequest($method, $request);

    flagCheck($error_flag, 'admin', 'editPageUser?id=' . $id);

    $error_flag += validateID($id);

    flagCheck($error_flag, 'admin', 'editPageUser?id=' . $id);

    $error_flag += validateAllUpdateInputForUser();

    flagCheck($error_flag, 'admin', 'editPageUser?id=' . $id);
    return true;
}




