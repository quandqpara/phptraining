<?php
require_once('controllers/base_controller.php');
require_once('model/AdminModel.php');
require_once('model/UserModel.php');
require_once('validation/validation.php');
require_once('Helper/common.php');

class adminController extends BaseController
{
    public $dbResult = [];

    public function __construct()
    {
        $this->folder = 'admin';
        $this->adminModel = new AdminModel();
        $this->userModel = new UserModel();
    }

    //-----------------------------------------------------VIEW SECTION-------------------------------------------------
    //login
    public function index()
    {
        return $this->render('index');
    }

    //search page/ home page
    public function home()
    {
        return $this->render('home');
    }

    //create admin page
    public function createPageAdmin()
    {
        return $this->render('createAdmin');
    }

    //edit admin page
    public function editPageAdmin()
    {
        if (isset($_GET['id'])) {
            $updatingAdminInfo = $this->adminModel->searchOneAdmin($_GET['id']);
        }
        return $this->render('editAdmin', ['targetAdminToUpdate' => $updatingAdminInfo]);
    }

    //search user page
    public function searchPageUser()
    {
        return $this->render('searchUser');
    }

    //edit user page
    public function editPageUser()
    {
        if (isset($_GET['id'])) {
            $updatingUserInfo = $this->userModel->searchOneUser($_GET['id']);
        }
        return $this->render('editUser', ['targetUserToUpdate' => $updatingUserInfo]);
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
            $returnData = $this->adminModel->basicLogin($email, $password);

            if (checkEmptyReturnData($returnData)) {
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

    //logout
    //clear session back to first page.
    function logout()
    {
        session_unset();
        header('Location: /admin/index');
        exit;
    }

    //----------------------------------------------------ADMIN SECTION-------------------------------------------------
    //normal Admin check
    function isPermissionAdmin()
    {
        if (!isAdmin()) {
            $_SESSION['flash_message']['permission']['no_permission_admin'] = getMessage('no_permission_admin');
            header('Location: /user/profile');
        }

        return true;
    }

    //superAdmin check
    function permissionCheck()
    {
        if (!isAdmin()) {
            $_SESSION['flash_message']['permission']['no_permission_admin'] = getMessage('no_permission_admin');
            header('Location: /user/profile');
        }

        if (!isSuperAdmin()) {
            $_SESSION['flash_message']['permission']['no_permission_super_admin'] = getMessage('no_permission_super_admin');
            header('Location: /admin/home');
        }
        return true;
    }

    //handle avatar
    function handleAvatar()
    {
        $error = 0;
        if (isset($_POST)) {
            $tempname = $_FILES['avatar']['tmp_name'];
            $folder = ROOT . "/uploads/avatar/";

            $error += validateAvatar('avatar');

            //if no error was found, save the image to an actual folder.
            if ($error == 0) {
                $fileType = getFileType($_FILES['avatar']['type']);
                $fileNameAfterSaved = renameUploadImage($_POST['email']) . '-avatar.' . $fileType;
                $folder .= $fileNameAfterSaved;
                if (file_exists($folder)) {
                    unlink($folder);
                    move_uploaded_file($tempname, $folder);
                } else {
                    move_uploaded_file($tempname, $folder);
                }
                return $folder;
            }
        }
        //else return error
        return $error;
    }

    //create - ADMIN(super)
    //Must be admin to create new admin
    function createAdmin()
    {
        unsetAll();
        //check for permission - if there is no permission back to homepage
        $this->permissionCheck();

        $method = $_SERVER['REQUEST_METHOD'];
        $request = $_POST;

        //check validity of the input
        //if not pass return
        //if passed try to create
        $avatarLink = $this->handleAvatar();
        validateAdminCreateForm($method, $avatarLink);

        //if it can pass validateAdminCreateForm
        //Correcting the $_REQUEST before passing it to the query
        $_REQUEST['avatar'] = $avatarLink;

        //try to create (call create from module)
        $infoArrayForCreateAccount = $this->getInfoForCreateNewAdmin();
        $this->adminModel->create($infoArrayForCreateAccount);

        //redirect to create Screen with messages
        retrieveOldFormData();
        header('Location: /admin/createPageAdmin');
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
        unsetAll();
        //permission check
        $this->permissionCheck();

        //validate input
        $method = $_SERVER['REQUEST_METHOD'];
        $request = $_POST;

        if (!isset($_SESSION['flash_message']['update_target']['id'])) {
            $_SESSION['flash_message']['update_id']['not_found'] = getMessage('no_id_found');
        }
        $id = $_SESSION['flash_message']['update_target']['id'];
        $location = '/admin/editPageAdmin?id=' . $id;

        if (!validateUpdateForm($method, $request, $id)) {
            retrieveOldFormData();
            header('Location: ' . $location);
            exit;
        }

        //try to update (input id and value to change)
        $this->adminModel->update($id, $request);

        retrieveOldFormData();
        header('Location: ' . $location);
        exit;
    }

    //search - ADMIN(super)
    function searchAdmin()
    {
        unsetAll();
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
        //unset $_SESSION [flash_mess] and [old_data]
        unsetAll();
        //permission check
        $this->permissionCheck();

        //collecting $id to delete from GET
        $id = null;

        //if $_GET['id'] is empty or not set back to search with message
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            $_SESSION['flash_message']['id']['no_id_found'] = getMessage('no_id_found');
            header('Location: /admin/home');
            exit;
        }

        $id = $_GET['id'];
        //if $id failed validate
        if (validateID($id) !== 0) {
            $_SESSION['flash_message']['id']['invalid'] = getMessage('invalid_id');
            header('Location: /admin/home');
            exit;
        }

        //if $id passed validate
        //delete
        $this->adminModel->deleteById($id);
        header('Location: /admin/home');
        exit;
    }

    //-----------------------------------------------------USER SECTION-------------------------------------------------
    //search - USER(admin)
    function searchUser()
    {
        unsetAll();
        //permission check
        $this->isPermissionAdmin();

        //validate input
        $method = $_SERVER['REQUEST_METHOD'];

        if (!validateSearchFormForUser($method)) {
            header('Location: /admin/searchPageUser');
            exit;
        }

        $emailPhrase = $_GET['email'];
        $namePhrase = $_GET['name'];
        $page = 1;
        if (isset($_GET['page']) && is_numeric($_GET['page'])) {
            $page = $_GET['page'];
        }

        //search
        $result = $this->userModel->findByEmailAndName($emailPhrase, $namePhrase, $page);
        if (isset($result)) {
            $_SESSION['flash_message']['search']['success'] = getMessage('search_success');
        }
        $this->render('searchPageUser', ['data' => $result]);
    }

    //edit/update - USER(admin)
    function editUser()
    {
        unsetAll();
        //permission check
        $this->isPermissionAdmin();

        //validate input
        $method = $_SERVER['REQUEST_METHOD'];
        $request = $_POST;

        if (!isset($_SESSION['flash_message']['update_target']['id'])) {
            $_SESSION['flash_message']['update_id']['not_found'] = getMessage('no_id_found');
        }
        $id = $_SESSION['flash_message']['update_target']['id'];
        $location = '/admin/editPageUser?id=' . $id;

        if (!validateUpdateFormForUser($method, $request, $id)) {
            retrieveOldFormData();
            header('Location: ' . $location);
            exit;
        }

        //try to update (input id and value to change)
        $this->userModel->update($id, $request);

        retrieveOldFormData();
        header('Location: ' . $location);
        exit;
    }

    //delete - USER(admin)
    function deleteUser()
    {
        //unset $_SESSION [flash_mess] and [old_data]
        unsetAll();
        //permission check
        $this->isPermissionAdmin();

        //collecting $id to delete from GET
        $id = null;

        //if $_GET['id'] is empty or not set back to search with message
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            $_SESSION['flash_message']['id']['no_id_found'] = getMessage('no_id_found');
            header('Location: /admin/searchPageUser');
            exit;
        }

        $id = $_GET['id'];
        //if $id failed validate
        if (validateID($id) !== 0) {
            $_SESSION['flash_message']['id']['invalid'] = getMessage('invalid_id');
            header('Location: /admin/searchPageUser');
            exit;
        }

        //if $id passed validate
        //delete
        $this->userModel->deleteById($id);
        header('Location: /admin/searchPageUser');
        exit;
    }

}