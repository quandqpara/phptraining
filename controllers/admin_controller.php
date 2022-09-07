<?php
require_once('controllers/base_controller.php');
require_once('model/admin.php');
require_once('validation/validation.php');

class adminController extends BaseController
{
    public function __construct()
    {
        $this->folder = 'admin';
    }

    public function index()
    {
        return $this->render('index');
    }

    public function home()
    {
        return $this->render('home');
    }

    public function auth()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $request = $_REQUEST;

        //check valid request method & valid input submission
        if ($this->validateInput($method, $request)) {
            $password = $request['password'];
            $email = $request['email'];

            //check account validity
            $returnData = Admin::admin_login($email, $password);
            if ($this->checkReturnData($returnData)) {
                $this->sessionUserSetter($returnData);
                $message = $_SESSION['session_user']['name'] . getMessage('login_success');
                $_SESSION['flash_message']['logged_in'] = $message;
                header('Location: /admin/home');
                exit;
            } else {
                $_SESSION['flash_message']['not_logged_in'] = getMessage('login_failed');
                $_SESSION['old_data']['email'] = $email;
                header('Location: /admin/index');
                exit;
            }
        }
    }

    public function sessionUserSetter($data) {
        $_SESSION['session_user']['id'] = $data[0]['id'];
        $_SESSION['session_user']['name'] = $data[0]['name'];
        $_SESSION['session_user']['email'] = $data[0]['email'];
        $_SESSION['session_user']['avatar'] = $data[0]['avatar'];
        $_SESSION['session_user']['role_type'] = $data[0]['role_type'];
    }


    public function checkReturnData($array){
        if(!empty($array)){
            return true;
        }
    }

    public function validateInput($method, $request)
    {
        $error_flag = 0;

        if ($method != 'POST') {
            $_SESSION['flash_message']['request_wrong'] = getMessage('invalid_request');
            $error_flag += 1;
        }

        if (empty($request)) {
            $_SESSION['flash_message']['request_empty'] = getMessage('request_empty');
            $error_flag += 1;
        }

        if (!isset($request['email'])) {
            $_SESSION['old_data']['email'] = $request['email'];
            $_SESSION['flash_message']['email_empty'] = getMessage('email_empty');
            $error_flag += 1;
        }

        if (!validateEmail($request['email'])){
            $_SESSION['old_data']['email'] = $request['email'];
            $_SESSION['flash_message']['email_incorrect'] = getMessage('invalid_email');
            $error_flag += 1;
        }

       if (!isset($request['password'])) {
           $_SESSION['flash_message']['password_empty'] = getMessage('password_empty');
           $error_flag += 1;
        }

        if (!validatePassword($request['password'])){
            $_SESSION['flash_message']['password_incorrect'] = getMessage('invalid_password');
            $error_flag += 1;
        }

        if ($error_flag >= 1){
            $_SESSION['flash_message']['number_of_error'] = $error_flag;
            header('Location: /admin/index');
            exit;
        }
        return true;
    }
}