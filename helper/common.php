<?php

function setSessionAdmin($role)
{
    $_SESSION['admin']['role'] = (int)$role;
}

function getAdminID()
{
    if ($_SESSION['admin']) {
        return $_SESSION['session_user']['id'];
    }
}

function getUserID()
{
    if ($_SESSION['user']) {
        return $_SESSION['session_user']['id'];
    }
}

function setSessionUser()
{
    $_SESSION['user'] = true;
}

function isAdmin()
{
    if (!empty($_SESSION['admin'])) {
        return true;
    } else {
        $_SESSION['flash_message']['permission']['no_permission_admin'] = getMessage('no_permission_admin');
        return false;
    }
}

function isSuperAdmin()
{
    if (isAdmin() && $_SESSION['admin']['role'] == 2) {
        return true;
    } else {
        $_SESSION['flash_message']['permission']['no_permission_super_admin'] = getMessage('no_permission_super_admin');
        return false;
    }
}

function isUser()
{
    if ($_SESSION['user']) {
        return true;
    }
    return false;
}

function isLoggedIn()
{

    if (isset($_SESSION['admin']['role']) && $_SESSION['admin']['role'] == 1) {
        $_SESSION['flash_message']['require']['require_logout'] = getMessage('admin_logged_in');
        header('Location: /management/user/searchPageUser');
        exit;
    }

    if (isset($_SESSION['admin']['role']) && $_SESSION['admin']['role'] == 2) {
        $_SESSION['flash_message']['require']['require_logout'] = getMessage('super_admin_logged_in');
        header('Location: /management/admin/home');
        exit;
    }

    if (isset($_SESSION['user'])) {
        $_SESSION['flash_message']['require']['require_logout'] = getMessage('user_logged_in');
        header('Location: /frontend/front/profile');
        exit;
    }
}

function basicUserSetter($data)
{
    $_SESSION['session_user']['id'] = $data[0]['id'];
    $_SESSION['session_user']['name'] = $data[0]['name'];
    $_SESSION['session_user']['email'] = $data[0]['email'];
    $_SESSION['session_user']['avatar'] = $data[0]['avatar'];
}

//________________________________________________________debug_________________________________________________________
function showLog($data, $continue = false)
{
    echo "<pre>";
    var_dump($data);
    echo "</pre>";

    if (!$continue) {
        die();
    }
}

//__________________________________________________helper functions____________________________________________________
function writeLog($log)
{
    $logFile = fopen("log.txt", "a") or die("Unable to open file");
    $log .= "\n";
    fwrite($logFile, $log);
    fclose($logFile);
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

function MBToByte($size)
{
    return 1024 * 1024 * $size;
}

function getTitle()
{
    $title = $_SESSION['page_title'];
    switch ($title) {
        case"index":
            if (str_contains($_SERVER['REQUEST_URI'], 'front')) {
                $title = 'User Login';
            } elseif (str_contains($_SERVER['REQUEST_URI'], 'management')) {
                $title = 'Admin Login';
            }
            break;
        case "profile":
            $title = 'Profile';
            break;
        case "home":
            $title = 'Home';
            break;
        case "editUser":
        case "editPageUser":
            $title = 'Edit User';
            break;
        case "searchAdmin":
            $title = 'Search Admin';
            break;
        case "editAdmin":
        case "editPageAdmin":
            $title = 'Edit Admin';
            break;
        case "creatAdmin":
        case "createPageAdmin":
            $title = 'Create Admin';
            break;
        case "searchUser":
        case "searchPageUser":
            $title = 'Search User';
            break;
    }
    return $title;
}

function savePreviousPageURI()
{
    $_SESSION['previous-page'] = $_SERVER['REQUEST_URI'];
}

function clearTemp(){
    if(isset($_SESSION['avatar_temp'])){
        if(file_exists($_SESSION['avatar_temp'])){
            unlink($_SESSION['avatar_temp']);
            unset($_SESSION['avatar_temp']);
        }
    }
}

//____________________________________________________handling notice message___________________________________________
function getMessage()
{
    $_errorMessages = '';
    //get all possible error messages
    if (empty($_errorMessages)) {
        $_errorMessages = require ROOT . '/config/message.php';
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

    //check if there is any unread message
    if (isset($_SESSION['flash_message']) && empty($_SESSION['flash_message'])) {
        unset($_SESSION['flash_message']);
        return $tempMessage;
    }

    //if ['flash_message'] . . . exist and has item
    //then check if ['flash_message']['item'].... contain message
    //only print first message from that ['item'][...] array
    //remove that section from 'flash_message'
    if (isset($_SESSION['flash_message']) && !empty($_SESSION['flash_message'])) {
        if (!empty($_SESSION['flash_message'][$message])) {
            $arrayMessage = $_SESSION['flash_message'][$message];
            $tempMessage = array_shift($arrayMessage);
        }
        unset($_SESSION['flash_message'][$message]);
    }

    return $tempMessage;
}

//______________________________________filling old data from previous input form_______________________________________
function retrieveOldFormData()
{
    if (isset($_SESSION['old_data'])) {
        unset($_SESSION['old_data']);
    }

    foreach ($_REQUEST as $key => $item) {
            $_SESSION['old_data'][$key] = $item;
    }

    $ignoreData = array('password', 'verify', 'role_type', 'status');
    foreach ($ignoreData as $item) {
        if(isset($_SESSION['old_data'][$item])){
            unset($_SESSION['old_data'][$item]);
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

//__________________________________________________displaying html_____________________________________________________
function loadPaginator($data)
{
    $reloadUrl = ltrim(strstr($_SERVER['REQUEST_URI'], '?'), '?');

    //remove duplicate params
    $correctingUrl = explode("&", $reloadUrl);
    $reloadUrl = "";
    if (isset($correctingUrl)) {
        foreach ($correctingUrl as $param) {
            $temp = explode("=", $param);
            $key = $temp[0];
            $value = $temp[1];
            if (!str_contains($reloadUrl, $key)) {
                $reloadUrl .= $key . "=" . $value . "&";
            }
        }
    }
    $href = rtrim($_SERVER['REDIRECT_URL'] . "?" . $reloadUrl, '&');
    if (str_contains($href, '&page=')) {
        $href = strstr($href, '&page=', true);
    }

    $href_first = $href . "&page=" . 1;
    $href_prev = $href . "&page=" . $data['pagination']['prev'];
    $href_page = $href . "&page=";
    $href_next = $href . "&page=" . $data['pagination']['next'];
    $href_last = $href . "&page=" . $data['pagination']['totalPages'];
    //print Pagination
    if (!empty($data)) {
        $pageLink = "<ul class='pagination'>";
        $pageLink .= "<li class='page-item'><a class='page-link' href='" . $href_first . "'>&laquo</a></li>";
        $pageLink .= "<li class='page-item'><a class='page-link' href='" . $href_prev . "'>Previous</a></li>";
        for ($i = 1; $i <= $data['pagination']['totalPages']; $i++) {
            if($data['pagination']['page'] == $i){
                $pageLink .= "<li class='page-item'><a class='page-link active' href='" . $href_page . $i . "'>" . $i . "</a></li>";
            }
            else {
                $pageLink .= "<li class='page-item'><a class='page-link' href='" . $href_page . $i . "'>" . $i . "</a></li>";
            }
        }
        $pageLink .= "<li class='page-item'><a class='page-link' href='" . $href_next . "'>Next</a></li>";
        $pageLink .= "<li class='page-item'><a class='page-link' href='" . $href_last . "'>&raquo</a></li>";
        echo $pageLink . "</ul>";
    }
}

function displayingSortIcon($data)
{
    if (!empty($data['data'])) {
        echo "<i class=\"fa fa-sort\" style=\"font-size:20px\"></i>";
    }
}

function displayTableResult($data)
{
    if (!isset($data['data']) || count($data['data']) == 0) {
        $searchTable = "<tr>";
        $searchTable .= "<td colspan='6'><span>No Results Found!</span></td>";
        echo $searchTable . "</tr>";
    } else {
        foreach ($data['data'] as $result) {
            $searchTable = "<tr>";
            $searchTable .= "<td>" . $result['id'] . "</td>";

            $imagePath = $result['avatar'];
            $correctPath = '';
            if (!empty($imagePath)) {
                $correctPath = strstr($imagePath, '/uploads');
                $correctPath = "<div class=\"fill\"><img src=\"" . $correctPath . "\"></div>";
            } else if (empty($imagePath)) {
                $correctPath = "<div class=\"fill\"><img src=\"/uploads/avatar/default-user-avatar.png\"></div>";
            }
            $searchTable .= "<td style=\"    display: flex;
                                            justify-content: center;
                                       \">" . $correctPath . "</td>";

            $searchTable .= "<td>" . $result['name'] . "</td>";
            $searchTable .= "<td>" . $result['email'] . "</td>";

            $role = '';
            if (!empty($result['role_type'])) {
                $role = $result['role_type'];
                switch ($role) {
                    case 1:
                        $role = 'Admin';
                        break;
                    case 2:
                        $role = 'Super Admin';
                        break;
                }
            }
            $searchTable .= "<td>" . $role . "</td>";
            savePreviousPageURI();
            $searchTable .= " <td>
                        <div class=\"row g-2 align-items-center\">
                            <div class=\"col-auto\">
                                    <a class=\"disguised-button edit-btn\" href=\"/management/admin/editPageAdmin?id=" . $result['id'] . "\">Edit</a> 
                            </div>
                            <div class=\"col-auto\">
                                    <a  class=\"disguised-button delete-btn confirmation\" 
                                        href=\"/management/admin/deleteAdmin?id=" . $result['id'] . "\"
                                        onclick=\"return confirm('Are you sure?')\"
                                    >
                                        Delete
                                    </a>
                            </div>
                        </div>
                    </td>";
            echo $searchTable . "</tr>";
        }
    }
}

function displayTableResultForUserSearch($data, $controller)
{
    if (!isset($data['data']) || count($data['data']) == 0) {
        $searchTable = "<tr>";
        $searchTable .= "<td colspan='6'><span>No Results Found!</span></td>";
        echo $searchTable . "</tr>";
    } else {
        foreach ($data['data'] as $result) {
            $searchTable = "<tr>";
            $searchTable .= "<td>" . $result['id'] . "</td>";

            $imagePath = $result['avatar'];
            if (!empty($imagePath)) {
                $correctPath = "<div class=\"fill\"><img src=\"" . $imagePath . "\"></div>";
            } else if (empty($imagePath)) {
                $correctPath = "<div class=\"fill\"><img src=\"/uploads/avatar/default-user-avatar.png\"></div>";
            }
            $searchTable .= "<td style=\"    display: flex;
                                            justify-content: center;
                                       \">" . $correctPath . "</td>";

            $searchTable .= "<td>" . $result['name'] . "</td>";
            $searchTable .= "<td>" . $result['email'] . "</td>";

            $status = '';
            if (!empty($result['status'])) {
                switch ($result['status']) {
                    case '1':
                        $status = 'Active';
                        break;
                    case '2':
                        $status = 'Banned';
                        break;
                }
            }

            $searchTable .= "<td>" . $status . "</td>";
            savePreviousPageURI();
            $searchTable .= " <td>
                        <div class=\"row g-2 align-items-center\">
                            <div class=\"col-auto\">
                                    <a class=\"disguised-button edit-btn\" href=\"/management/" . $controller . "/editPageUser?id=" . $result['id'] . "\">Edit</a> 
                            </div>
                            <div class=\"col-auto\">
                                    <a  class=\"disguised-button delete-btn confirmation\" 
                                        href=\"/management/" . $controller . "/deleteUser?id=" . $result['id'] . "\"
                                        onclick=\"return confirm('Are you sure?')\"
                                    >
                                        Delete
                                    </a>
                            </div>
                        </div>
                    </td>";
            echo $searchTable . "</tr>";
        }
    }
}

function prepareColumnSort($columnName, $defaultDirection){
    $orderDirection = !empty($_GET['dir']) ? $_GET['dir'] : 'ASC';

    $href = $_SERVER['REQUEST_URI'];
    if (!empty($_GET)) {
        $href = $_SERVER['REQUEST_URI'];
        $sortDirection = "&dir=";
        $column = "&col=".$columnName;

        //prepare sort direction
        if(isset($_GET['col'])){
            if($_GET['col'] == $columnName)   //if this col was selected previously
            {
                $correctDirection = ($_GET['dir'] == $defaultDirection) ? "DESC" : "ASC";
                $sortDirection .= $correctDirection;
            }
            else  //if selected col is different from previous selected col
            {
                $sortDirection .= $defaultDirection;
            }
        }
        else    //if col was never set
        {
            $sortDirection .= $defaultDirection;
        }

        //remove previous col and dir from url
        $arr = explode( '&', $href);
        foreach ($arr as $item){
            if(str_contains($item, 'col') || str_contains($item, 'dir')){
                $pattern = '&'.$item;
                $href = str_replace($pattern, '', $href);
            }
        }

        //rebuild url
        echo $href. $column . $sortDirection;
    }
}

function displayNoticeMessage(array $possibleMessages){
    error_reporting(E_PARSE || E_ERROR);
    $acceptableMessage = $possibleMessages;
    foreach ($_SESSION['flash_message'] as $key => $value) {
        if (in_array($key, $acceptableMessage)) {
            if (isset($_SESSION['flash_message'][$key])) {
                echo "
                            <div class=\"w-80 mt-3 mb-3 notification border border-success rounded\">
                            <span class=\"noti-message h-100 d-flex align-text-center justify-content-center align-items-center\">"; ?>
                <?php
                if (isset($_SESSION['flash_message'][$key])) {
                    echo handleFlashMessage($key);
                }
                echo "</span>
                    </div>";
            }
        }
    }
}