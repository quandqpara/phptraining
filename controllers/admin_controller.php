<?php
require_once('controllers/base_controller.php');
require_once('model/AdminModel.php');
require_once('validation/validation.php');
require_once('Helper/common.php');

class adminController extends BaseController
{
    public $dbResult = [];

    public function __construct()
    {
        $this->folder = 'admin';
        $this->adminModel = new AdminModel();
    }

    //-----------------------------------------------------VIEW SECTION-------------------------------------------------
    public function index()
    {
        return $this->render('index');
    }

    public function home()
    {
        return $this->render('home');
    }

    public function createPageAdmin()
    {
        return $this->render('createAdmin');
    }

    public function createPageUser()
    {
        return $this->render('createUser');
    }

    public function editPageAdmin()
    {
        return $this->render('editAdmin');
    }

    public function editPageUser()
    {
        return $this->render('editUser');
    }

    //-----------------------------------------------------AUTH SECTION-------------------------------------------------
    //login
    public function auth()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        //if the input failed the validate return to login
        if (!validateLoginInput($method)) {

            header('Location: /admin/index');
            exit;
        }

        //if it passed
        if (validateLoginInput($method)) {
            $password = $_REQUEST['password'];
            $email = $_REQUEST['email'];


            //check account validity in DB
            //if return data contain data -> confirmed log in
            //....else no data found back to login
            $returnData = $this->adminModel->adminLogin($email, $password);

            if ($this->checkReturnData($returnData)) {
                setSessionAdmin($returnData[0]['role_type']);                                                           // set admin session.
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
    private function sessionAdminSetter($data)
    {
        basicUserSetter($data);
        $_SESSION['session_user']['role_type'] = $data[0]['role_type'];
    }

    private function checkReturnData($array)
    {
        if (!empty($array)) {
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

    //----------------------------------------------------ADMIN SECTION-------------------------------------------------
    function permissionCheck()
    {
        if (!isAdmin()) {
            header('Location: /user/home');
        }

        if (!isSuperAdmin()) {
            header('Location: /admin/createUser');
        }
        return true;
    }

    //create - ADMIN(super)
    //Must be admin to create new admin
    function createAdmin()
    {
        //check for permission
        $this->permissionCheck();

        $method = $_SERVER['REQUEST_METHOD'];

        //check validity of the input
        //if not pass return
        //if passed try to create
        if (!validateAdminCreateForm($method, $_REQUEST)) {
            header('Location: /admin/createAdmin');
            exit;
        }

        //try to create (call create from module)
        $infoArrayForCreateAccount = $this->getInfoForCreateNewAdmin();
        $this->adminModel->create($infoArrayForCreateAccount);

        //redirect to create Screen with messages
        retrieveOldFormData();
        header('Location: /admin/createAdmin');
        exit;
    }

    //return array of key and corresponding value ('name', 'password', 'email', 'avatar', 'role_type', 'ins_id', 'ins_datetime')
    private function getInfoForCreateNewAdmin()
    {
        $infoArray = array();
        $infoNeeded = array('name', 'password', 'email', 'avatar', 'role_type');
        foreach ($infoNeeded as $item) {
            array_push($infoArray, $_REQUEST[$item]);
        }
        return array_combine($infoNeeded, $infoArray);
    }

    //update - ADMIN(super)
    function editAdmin()
    {
        //permission check
        $this->permissionCheck();

        //validate input
        $method = $_SERVER['REQUEST_METHOD'];
        $request = $_POST;

        if (!isset($_SESSION['flash_message']['update_target']['id'])){
            $_SESSION['flash_message']['update_id']['not_found'] = getMessage('no_id_found');
        }
        $id = $_SESSION['flash_message']['update_target']['id'];
        $location = '/admin/editPageAdmin?id='.$id;

        if (!validateUpdateForm($method, $request, $id)) {
            retrieveOldFormData();
            header('Location: '.$location);
            exit;
        }

        //try to update (input id and value to change)
        $this->adminModel->update($id, $request);
        showLog($request);

        retrieveOldFormData();
        header('Location: '.$location);
        exit;
    }

    //search - ADMIN(super)
    function searchAdmin()
    {
        //permission check
        $this->permissionCheck();

        //validate input
        $method = $_SERVER['REQUEST_METHOD'];

        if (!validateSearchForm($method)) {
            header('Location: /admin/home');
            exit;
        }

        $emailPhrase = $_GET['email'];
        $namePhrase = $_GET['name'];
        $page = 1;
        if (isset($_GET['page']) && is_numeric($_GET['page'])) {
            $page = $_GET['page'];
        }

        //search
        $result = $this->adminModel->findByEmailAndName($emailPhrase, $namePhrase, $page);
        if (isset($result)) {
            $_SESSION['flash_message']['search']['success'] = getMessage('search_success');
        }
        $this->render('home', ['data' => $result]);
    }

    //delete - ADMIN(super)
    function deleteAdmin()
    {
        if (!isSuperAdmin()) {
            setFlashMessage(getMessage('no_permission'));

            header('Location: /');
        }
    }

    //-----------------------------------------------------USER SECTION-------------------------------------------------
    //search - USER(admin)
    function searchUser()
    {

    }

    //edit/update - USER(admin)
    function editUser()
    {

    }

    //delete - USER(admin)
    function deleteUser()
    {

    }

}