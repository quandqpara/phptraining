<?php
require_once('controllers/base_controller.php');
require_once('model/AdminModel.php');
require_once('validation/validation.php');

class adminController extends BaseController
{
    public function __construct()
    {
        $this->folder = 'admin';
        $this->adminModel = new AdminModel();
    }

    public function index()
    {
        return $this->render('index');
    }

    public function home()
    {
        return $this->render('home');
    }

    public function createPage(){
        return $this->render('create');
    }

    //login
    public function auth()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $request = $_REQUEST;

        //check valid request method & valid input submission
        if (validateLoginInput($method, $request)) {
            $password = $request['password'];
            $email = $request['email'];

            //check account validity in DB
            //if return data contain data -> confirmed log in
            //....else no data found back to login
            $returnData = $this->adminModel->admin_login($email, $password);
            if ($this->checkReturnData($returnData)) {
                setSessionAdmin($_SESSION['session_user']['id'], 1);                                                    // set admin session.
                $this->sessionAdminSetter($returnData);                                                                 // set admin info
                $message = $_SESSION['session_user']['name'] . getMessage('login_success');
                $_SESSION['flash_message']['login']['logged_in'] = $message;
                header('Location: /admin/home');
                exit;
            } else {
                $_SESSION['flash_message']['login']['not_logged_in'] = getMessage('login_failed');
                $_SESSION['old_data']['email'] = $email;
                header('Location: /admin/index');
                exit;
            }
        }
    }

    //set logged-in user data to session for later use
    private function sessionAdminSetter($data) {
        basicUserSetter($data);
        $_SESSION['session_user']['role_type'] = $data[0]['role_type'];
    }

    private function checkReturnData($array){
        if(!empty($array)){
            return true;
        }
    }

    //logout
    //clear session back to first page.
    function logout()
    {
        session_unset();
        header('Location: /admin/index');
        exit;
    }

    function create($data){
    }
}