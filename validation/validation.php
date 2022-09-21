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
    //min length 8 max length 99 chars
    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,99}$/', $password)) {
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
    if (!isset($_FILES[$avatar]) || (isset($_FILES[$avatar]) && empty($_FILES[$avatar]['name']))) {
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

    if (!in_array($_FILES[$avatar]['type'], IMAGE_UPLOAD_FILE_TYPE)) {
        $_SESSION['flash_message']['avatar']['type'] = getMessage('invalid_avatar');
        $flag += 1;
    }
    return $flag;
}

function handleAvatar()
{
    $error = 0;

    //if there is no submission and the user has the data already, skip             (submit: no, user had it: yes)
    if(isset($_FILES) && empty($_FILES['avatar']['name'])){
        if(isset($_SESSION['targetToEdit']['0']['avatar']) && !empty($_SESSION['targetToEdit']['0']['avatar'])){
            return $error;
        }
    }

    //there is no submission nor user have it                                        (submit: no, user had it: no)
    if(empty($_SESSION['targetToEdit']['0']['avatar']) && empty($_FILES['avatar']['name'])){
        $error += validateAvatar('avatar');
    }

    //if there is submission, handle the submission                                 (submit: yes, user had it: dont care)
    if(isset($_FILES) && !empty($_FILES['avatar']['name'])){
        $tempname = $_FILES['avatar']['tmp_name'];
        $folder = ROOT . "/uploads/avatar/";
        $tempFolder = ROOT . "/uploads/temp/";

        $error += validateAvatar('avatar');

        //if no error was found, save the image to an actual folder.
        if ($error == 0) {
            $fileType = getFileType($_FILES['avatar']['type']);

            $fileNameBeforeSaved = renameUploadImage($_POST['email']) . '-avatar.' . $fileType;

            $folder .= $fileNameBeforeSaved;
            $tempFolder .= $fileNameBeforeSaved;

            $_SESSION['avatar_folder_when_success_update'] = $folder;
            $_SESSION['avatar_temp'] = $tempFolder;

            if (file_exists($tempFolder)) {
                unlink($tempFolder);
                move_uploaded_file($tempname, $tempFolder);
            } else {
                move_uploaded_file($tempname, $tempFolder);
            }
            return $error;
        }
    }

    //else return error
    return $error;
}

function validateName($name): int
{
    $flag = 0;
    if (empty($name)) {
        $_SESSION['flash_message']['name']['empty'] = getMessage('name_empty');
        $flag += 1;
    }

    //Must only contain letters
    if (!preg_match('/^([a-zA-ZÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂưăạảấầẩẫậắằẳẵặẹẻẽềềểỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ\s]+)$/i', $name)) {
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

function validateAdminRoles(): int
{
    $flag = 0;
    if (!isset($_REQUEST['role_type']) || empty($_REQUEST['role_type'])) {
        $_SESSION['flash_message']['role_type']['empty'] = getMessage('role_empty');
        $flag += 1;
        return $flag;
    }

    if ($_REQUEST['role_type'] >= 3 || $_REQUEST['role_type'] == 0) {
        $_SESSION['flash_message']['role_type']['invalid'] = getMessage('invalid_role');
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

    if ($status >= 3 || $status == 0) {
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
function flagCheck($flag): bool
{
    if ($flag > 0) {
        return false;
    }
    return true;
}

//----------------------------------ADMIN VALIDATION----------------------------------
//LOGIN
function validateLoginInput($method): bool
{
    $error_flag = 0;

    $error_flag += validateSubmitFormPostAndEmptyRequest($method, $_REQUEST);

    if (!flagCheck($error_flag)) {
        return false;
    }

    $error_flag += validateEmail($_REQUEST['email']);
    $error_flag += validatePassword($_REQUEST['password']);

    if (!flagCheck($error_flag)) {
        return false;
    }
    return true;
}

//CREATE
function validateAllInput(): int
{
    $flag = 0;
    $flag += validateName($_REQUEST['name']);
    $flag += validateEmail($_REQUEST['email']);
    $flag += validatePassword($_REQUEST['password']);
    $flag += validateVerifyPassword($_REQUEST['password'], $_REQUEST['verify']);
    $flag += validateAdminRoles();
    return $flag;
}

function validateAdminCreateForm($method): bool
{
    $error_flag = 0;
    $error_flag += validateSubmitFormPostAndEmptyRequest($method, $_POST);

    if (!flagCheck($error_flag)) {
        return false;
    }

    $theAvatar = handleAvatar();
    if ($theAvatar > 0){
        $error_flag += $theAvatar;
    } else {
        $_REQUEST['avatar'] = $theAvatar;
        $_POST['avatar'] = $theAvatar;
    }

    $error_flag += validateAllInput();

    //if flag check failed -> redirect
    if (!flagCheck($error_flag)) {
        return false;
    }
    //otherwise return true
    return true;
}

//UPDATE
//1. Check form REQUEST TYPE and REQUEST has value
//2. validate ID
//3. Check if front has entered password(optional input) or not
//3.1 if not remove $_POST['password'] and ['verify_password']
//3.2 if yes continue
//4. validate others field normally
function validateUpdateForm($method, $id): bool
{
    $error_flag = 0;

    $error_flag += validateSubmitFormPostAndEmptyRequest($method, $_POST);

    if (!flagCheck($error_flag)) {
        return false;
    }

    $theAvatar = handleAvatar();
    if ($theAvatar > 0){
        $error_flag += $theAvatar;
    } else {
        $_REQUEST['avatar'] = $theAvatar;
        $_POST['avatar'] = $theAvatar;
    }

    $error_flag += validateID($id);

    $error_flag += optionalUpdateInputCheck();

    $error_flag += validateAllUpdateRequiredInput();

    if (!flagCheck($error_flag)) {
        return false;
    }
    return true;
}

//name, email, role
function validateAllUpdateRequiredInput(): int
{
    $flag = 0;
    $flag += validateName($_POST['name']);
    $flag += validateEmail($_POST['email']);
    $flag += validateAdminRoles($_POST['role_type']);
    return $flag;
}

//password, verify_password
function optionalUpdateInputCheck(): int
{
    $flag = 0;

    //is password and verify set?
    if (!isset($_POST['password']) && !isset($_POST['verify'])) {
        //they are not set, it's ok, optional, no error, go back
        return $flag;
    }

    if (isset($_POST['password']) && isset($_POST['verify'])) {
        //they are set, it's ok,
        //first check their emptiness
        //----> if empty, just remove them from $_post, and return
        if (empty($_POST['password'])) {
            unset($_POST['password']);
            unset($_POST['verify']);
            return $flag;
        }

        //----> else, move to second
        //second check for proper password and verify identical
        $flag += validatePassword($_POST['password']);
        $flag += validateVerifyPassword($_POST['password'], $_POST['verify']);

        return $flag;
    }
    return $flag;
}

//SEARCH
function validateSearchForm($method): bool
{
    $error_flag = 0;

    //method should be get and $_GET should not be empty
    $error_flag += validateSubmitFormGetAndEmptyRequest($method, $_GET);

    if (!flagCheck($error_flag)) {
        return false;
    }

    //don't have to validate anything more than empty input
    if (!isset($_GET['email']) || !isset($_GET['name'])) {
        $_SESSION['flash_message']['email']['empty'] = getMessage('email_empty');
        $_SESSION['flash_message']['name']['empty'] = getMessage('name_empty');
        return false;
    }

    return true;
}

//----------------------------------USER VALIDATION----------------------------------
function validateLoginInputForUser($method): bool
{
    $error_flag = 0;

    $error_flag += validateSubmitFormPostAndEmptyRequest($method, $_REQUEST);

    if (!flagCheck($error_flag)) {
        return false;
    }

    $error_flag += validateEmail($_REQUEST['email']);

    if ($_REQUEST['password'] == "") {
        return true;
    }

    $error_flag += validatePassword($_REQUEST['password']);
    if (!flagCheck($error_flag)) {
        return false;
    }
    return true;
}

function validateSearchFormForUser($method): bool
{
    $error_flag = 0;

    //method should be get and $_GET should not be empty
    $error_flag += validateSubmitFormGetAndEmptyRequest($method, $_GET);

    if (!flagCheck($error_flag)) {
        return false;
    }

    //don't have to validate anything more than empty input
    if (isset($_GET['email']) == false || isset($_GET['name']) == false) {
        $_SESSION['flash_message']['email']['empty'] = getMessage('email_empty');
        $_SESSION['flash_message']['name']['empty'] = getMessage('name_empty');
        return false;
    }
    return true;
}

function validateUpdateFormForUser($method, $id): bool
{
    $error_flag = 0;

    $error_flag += validateSubmitFormPostAndEmptyRequest($method, $_POST);

    if (!flagCheck($error_flag)) {
        return false;
    }

    $theAvatar = handleAvatar();
    if ($theAvatar > 0){
        $error_flag += $theAvatar;
    } else {
        $_REQUEST['avatar'] = $theAvatar;
        $_POST['avatar'] = $theAvatar;
    }

    $error_flag += validateID($id);

    $error_flag += optionalUpdateInputCheck();

    $error_flag += validateAllUpdateRequiredInputForUser();

    if (!flagCheck($error_flag)) {
        return false;
    }

    return true;
}

function validateAllUpdateRequiredInputForUser(): int
{
    $flag = 0;
    $flag += validateName($_POST['name']);
    $flag += validateEmail($_POST['email']);
    $flag += validateUsersStatus($_POST['status']);
    return $flag;
}




