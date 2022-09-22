<?php
require_once('controllers/base_controller.php');
require_once('model/AdminModel.php');
require_once('validation/validation.php');
require_once('helper/common.php');

class authController extends BaseController
{
    public function __construct()
    {
        $this->folder = 'auth';
        $this->adminModel = new adminModel();
    }
    //-----------------------------------------------------VIEW SECTION-------------------------------------------------
    //login
    public function index()
    {
        isLoggedIn();
        return $this->render('index');
    }

    //-----------------------------------------------------AUTH SECTION-------------------------------------------------
    //login
    public function login()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        //if the input failed the validate return to login
        if (!validateLoginInput($method)) {
            header('Location: /management/auth/index');
            exit;
        }

        //if it passed
        if (validateLoginInput($method)) {
            $password = $_REQUEST['password'];
            $email = $_REQUEST['email'];

            //check account validity in DB
            //if return data contain data -> confirmed log in
            //....else no data found back to login
            $returnData = $this->adminModel->basicLogin($email, $password);

            //if returnData is not empty -> front is found
            if (!empty($returnData)) {
                //check admin role
                if ($returnData['0']['role_type'] == 1) {
                    setSessionAdmin($returnData['0']['role_type']);
                    $this->sessionAdminSetter($returnData);
                    $message = $_SESSION['session_user']['name'] . getMessage('login_success');
                    $_SESSION['flash_message']['login']['logged_in'] = $message;
                    header('Location: /management/user/searchUser');
                    exit;
                } elseif ($returnData['0']['role_type'] == 2) {
                    setSessionAdmin($returnData['0']['role_type']);
                    $this->sessionAdminSetter($returnData);
                    $message = $_SESSION['session_user']['name'] . getMessage('login_success');
                    $_SESSION['flash_message']['login']['logged_in'] = $message;
                    header('Location: /management/admin/home');
                    exit;
                }
            } else {
                $_SESSION['flash_message']['login']['not_logged_in'] = getMessage('login_failed');
                $_SESSION['old_data']['email'] = $email;
                header('Location: /management/auth/index');
                exit;
            }
        }
    }

    //set logged-in front data to session for later use
    private function sessionAdminSetter($data)
    {
        basicUserSetter($data);
        $_SESSION['session_user']['role_type'] = $data[0]['role_type'];
    }

    //logout
    //clear session back to first page.
    function logout()
    {
        session_unset();
        $_SESSION['flash_message']['logout']['is_logged_out'] = getMessage('logout');
        header('Location: /management/auth/index');
        exit;
    }
}