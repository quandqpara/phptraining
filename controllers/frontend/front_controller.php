<?php

require_once('controllers/base_controller.php');
require_once('model/UserModel.php');
require_once('validation/validation.php');
require_once('helper/common.php');

include_once 'Facebook/facebook_api.php';

class frontController extends BaseController
{
    public function __construct()
    {
        $this->folder = 'front';
        $this->userModel = new UserModel();
    }

    public function index()
    {
        isLoggedIn();
        return $this->render('index');
    }

    public function profile()
    {
        return $this->render('profile');
    }

    public function auth()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        //if the input failed the validate return to login
        if (!validateLoginInputForUser($method)) {
            header('Location: /frontend/front/index');
            exit;
        }

        //if it passed
        $password = $_POST['password'];
        $email = $_POST['email'];

        //check account validity in DB
        //if return data contain data -> confirmed log in
        //....else no data found back to login
        $returnData = $this->userModel->basicLogin($email, $password);
        if (!empty($returnData)) {
            setSessionUser();                                                                                           // set front session.
            $this->sessionUserSetter($returnData);                                                                      // set front info
            $message = $_SESSION['session_user']['name'] . getMessage('login_success');
            $_SESSION['flash_message']['login']['logged_in'] = $message;
            header('Location: /frontend/front/profile');
            exit;
        } else {
            $_SESSION['flash_message']['login']['not_logged_in'] = getMessage('login_failed');
            $_SESSION['old_data']['email'] = $email;
            header('Location: /frontend/front/index');
            exit;
        }
    }

    public function logout()
    {
        session_unset();
        header('Location: /frontend/front/index');
        exit;
    }

    function processingFacebookData()
    {
        if (!isset($_SESSION['fb_user_info'])) {
            $_SESSION['flash_message']['login']['failed'] = getMessage('login_fb_failed');
            header('Location: frontend/front/index');
            exit;
        }
        $name = $_SESSION['fb_user_info']['first_name'] . $_SESSION['fb_user_info']['last_name'];
        $facebook_id = $_SESSION['fb_user_info']['id'];
        $email = $_SESSION['fb_user_info']['email'];
        $avatar = $_SESSION['fb_user_info']['picture']['data']['url'];

        unset($_SESSION['fb_user_info']);

        $userInfoFromFacebook = array(
            'name' => $name,
            'facebook_id' => $facebook_id,
            'email' => $email,
            'avatar' => $avatar,
        );

        $currentUser = $this->userModel->createUserWithInfoFromFacebook($userInfoFromFacebook);
        $_SESSION['session_user'] = $currentUser;
        if (!empty($currentUser)) {
            $_SESSION['flash_message']['login']['success'] = $userInfoFromFacebook['name'] . getMessage('login_success');
            setSessionUser();
            header('Location: /frontend/front/profile');
            exit;
        } else {

            $_SESSION['flash_message']['login']['failed'] = getMessage('login_failed');
            header('Location: /frontend/front/index');
            exit;
        }
    }

    private function sessionUserSetter(array $returnData)
    {
        basicUserSetter($returnData);
        $_SESSION['session_user']['status'] = $returnData[0]['status'];
    }
}