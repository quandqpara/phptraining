<?php

require_once ('controllers/base_controller.php');
require_once ('model/UserModel.php');
require_once('validation/validation.php');
require_once('Helper/common.php');

include_once 'Facebook/facebook_api.php';

class userController extends BaseController
{
    public function __construct()
    {
        $this->folder = 'user';
        $this->userModel = new UserModel();
    }

    public function index()
    {
        return $this->render('index');
    }

    public function profile()
    {
        return $this->render('profile');
    }

    public function auth(){
        $method = $_SERVER['REQUEST_METHOD'];

        //if the input failed the validate return to login
        if (!validateLoginInputForUser($method)) {

            header('Location: /user/index');
            exit;
        }

        //if it passed
        $password = $_REQUEST['password'];
        $email = $_REQUEST['email'];

        //check account validity in DB
        //if return data contain data -> confirmed log in
        //....else no data found back to login
        $returnData = $this->userModel->basicLogin($email, $password);

        if (checkEmptyReturnData($returnData)) {
            setSessionUser();                                                                                           // set user session.
            $this->sessionUserSetter($returnData);                                                                      // set user info
            $message = $_SESSION['session_user']['name'] . getMessage('login_success');
            $_SESSION['flash_message']['login']['logged_in'] = $message;
            header('Location: /user/profile');
            exit;
        } else {
            $_SESSION['flash_message']['login']['not_logged_in'] = getMessage('login_failed');
            $_SESSION['old_data']['email'] = $email;
            header('Location: /user/index');
            exit;
        }
    }

    public function logout(){
        session_unset();
        header('Location: /user/index');
        exit;
    }

    function processingFacebookData(){
        if(!isset($_SESSION['fb_user_info'])){
            $_SESSION['flash_message']['login']['failed'] = getMessage('login_fb_failed');
            header('Location: /user/index');
            exit;
        }
        $name = $_SESSION['fb_user_info']['first_name'].$_SESSION['fb_user_info']['last_name'];
        $facebook_id = $_SESSION['fb_user_info']['id'];
        $email = $_SESSION['fb_user_info']['email'];
        $avatar = $_SESSION['fb_user_info']['picture']['data']['url'];


        unset($_SESSION['fb_user_info']);

        $userInfoFromFacebook = array(
            'name'=> $name,
            'facebook_id' => $facebook_id,
            'email' => $email,
            'avatar' => $avatar,
            );

        $currentUser = $this->userModel->createUserWithInfoFromFacebook($userInfoFromFacebook);
        $_SESSION['session_user'] = $currentUser;
        if(!empty($currentUser)){
            $_SESSION['flash_message']['login']['success'] = $userInfoFromFacebook['name'].getMessage('login_success');
            header('Location: /user/profile');
            exit;
        } else {
            $_SESSION['flash_message']['login']['failed'] = getMessage('login_success');
            header('Location: /user/index');
            exit;
        }
    }

    private function sessionUserSetter(array $returnData)
    {
        basicUserSetter($returnData);
        $_SESSION['session_user']['status'] = $returnData[0]['status'];
    }
}