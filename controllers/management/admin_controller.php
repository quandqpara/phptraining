<?php
require_once('controllers/base_controller.php');
require_once('model/AdminModel.php');
require_once('model/UserModel.php');
require_once('validation/validation.php');
require_once('Helper/common.php');

class adminController extends BaseController
{
    public function __construct()
    {
        if (!isSuperAdmin()) {
            if (!isAdmin()) {
                session_unset();
                $_SESSION['flash_message']['permission']['no_permission'] = getMessage('no_permission_admin');
                header('Location: /management/auth/index');
                exit;
            } elseif (isAdmin()) {
                $_SESSION['flash_message']['permission']['no_permission'] = getMessage('no_permission_super_admin');
                header('Location: /management/user/searchUser');
                exit;
            }
        }
        $this->folder = 'admin';
        $this->adminModel = new AdminModel();
        $this->userModel = new UserModel();

    }

    //-----------------------------------------------------VIEW SECTION-------------------------------------------------
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
            $updatingAdminInfo = $this->adminModel->searchOneByID($_GET['id']);
        }
        return $this->render('editAdmin', ['targetAdminToUpdate' => $updatingAdminInfo]);
    }

    //search front page
    public function searchPageUser()
    {
        return $this->render('searchUser');
    }

    //edit front page
    public function editPageUser()
    {
        if (isset($_GET['id'])) {
            $updatingUserInfo = $this->userModel->searchOneByID($_GET['id']);
        }
        return $this->render('editUser', ['targetUserToUpdate' => $updatingUserInfo]);
    }


    //----------------------------------------------------ADMIN SECTION-------------------------------------------------
    //handle avatar
    function handleAvatar()
    {
        $error = 0;
        if (isset($_POST) && isset($_FILES)) {
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
        $method = $_SERVER['REQUEST_METHOD'];
        $request = $_POST;

        //check validity of the input
        //if not pass return with failed message
        //if passed try to create
        $avatarLink = $this->handleAvatar();
        if (!validateAdminCreateForm($method, $avatarLink)) {
            $_SESSION['flash_message']['create']['failed'] = getMessage('create_failed');
            retrieveOldFormData();
            header('Location: /management/admin/createPageAdmin');
            exit;
        }

        //if it can pass validateAdminCreateForm
        //Correcting the $_REQUEST before passing it to the query
        $_REQUEST['avatar'] = $avatarLink;

        //try to create (call create from module)
        $infoArrayForCreateAccount = $this->getInfoForCreateNewAdmin();

        $rowNum = $this->adminModel->create($infoArrayForCreateAccount);

        if ($rowNum == 0 || $rowNum > 1) {
            $_SESSION['flash_message']['create']['failed'] = getMessage('create_failed');
        } else if ($rowNum == 1) {
            $_SESSION['flash_message']['create']['success'] = getMessage('create_success');
        }

        //redirect to create Screen with success messages
        retrieveOldFormData();
        header('Location: /management/admin/createPageAdmin');
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
        //validate input
        $method = $_SERVER['REQUEST_METHOD'];

        if (!isset($_SESSION['flash_message']['update_target']['id'])) {
            $_SESSION['flash_message']['update_id']['not_found'] = getMessage('no_id_found');
        }
        $id = $_SESSION['flash_message']['update_target']['id'];
        $location = '/management/admin/editPageAdmin?id=' . $id;

        if (!validateUpdateForm($method, $id)) {
            retrieveOldFormData();
            $_SESSION['flash_message']['edit']['failed'] = getMessage('update_failed');
            header('Location: ' . $location);
            exit;
        }

        //try to update (input id and value to change)
        $rowAffected = $this->adminModel->update($id, $_POST);

        if ($rowAffected == 0 || $rowAffected > 1) {
            $_SESSION['flash_message']['edit']['failed'] = getMessage('update_failed');
        } else if ($rowAffected == 1) {
            $_SESSION['flash_message']['edit']['success'] = getMessage('update_success');
        }

        retrieveOldFormData();
        header('Location: ' . $location);
        exit;
    }

    //search - ADMIN(super)
    function searchAdmin()
    {
        //validate input
        $method = $_SERVER['REQUEST_METHOD'];

        if (!validateSearchForm($method)) {
            header('Location: /management/admin/home');
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
        $_SESSION['flash_message']['search']['success'] = getMessage('search_success');
        if (isset($result)) {
            $this->render('home', ['data' => $result]);
        } else {
            $this->render('home');
        }

    }

    //delete - ADMIN(super)
    function deleteAdmin()
    {
        //collecting $id to delete from GET
        $id = null;

        //if $_GET['id'] is empty or not set back to search with message
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            $_SESSION['flash_message']['id']['no_id_found'] = getMessage('no_id_found');
            header('Location: /management/admin/home');
            exit;
        }

        $id = $_GET['id'];
        //if $id failed validate
        if (validateID($id) !== 0) {
            $_SESSION['flash_message']['id']['invalid'] = getMessage('invalid_id');
            header('Location: /management/admin/home');
            exit;
        }

        //if $id passed validate
        //delete
        $rowChange = $this->adminModel->deleteById($id);
        if ($rowChange == 1) {
            $_SESSION['flash_message']['delete']['success'] = getMessage('delete_success');
        } else {
            $_SESSION['flash_message']['delete']['failed'] = getMessage('delete_failed');
        }

        header('Location: /management/admin/home');
        exit;
    }

    //-----------------------------------------------------USER SECTION-------------------------------------------------
    //search - USER(admin)
    function searchUser()
    {
        //validate input
        $method = $_SERVER['REQUEST_METHOD'];

        if (!validateSearchFormForUser($method)) {
            header('Location: /management/admin/searchPageUser');
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
        $_SESSION['flash_message']['search']['success'] = getMessage('search_success');
        if (isset($result)) {
            $this->render('searchUser', ['data' => $result]);
        } else {
            $this->render('searchUser');
        }
    }

    //edit/update - USER(admin)
    function editUser()
    {
        //validate input
        $method = $_SERVER['REQUEST_METHOD'];

        if (!isset($_SESSION['flash_message']['update_target']['id'])) {
            $_SESSION['flash_message']['update_id']['not_found'] = getMessage('no_id_found');
        }
        $id = $_SESSION['flash_message']['update_target']['id'];
        $location = '/management/admin/editPageUser?id=' . $id;

        if (!validateUpdateFormForUser($method, $id)) {
            retrieveOldFormData();
            $_SESSION['flash_message']['edit']['failed'] = getMessage('update_failed');
            header('Location: ' . $location);
            exit;
        }

        //try to update (input id and value to change)
        $rowAffected = $this->userModel->update($id, $_POST);

        if ($rowAffected == 0 || $rowAffected > 1) {
            $_SESSION['flash_message']['edit']['failed'] = getMessage('update_failed');
        } else if ($rowAffected == 1) {
            $_SESSION['flash_message']['edit']['success'] = getMessage('update_success');
        }

        retrieveOldFormData();
        header('Location: ' . $location);
        exit;
    }

    //delete - USER(admin)
    function deleteUser()
    {
        //collecting $id to delete from GET
        $id = null;

        //if $_GET['id'] is empty or not set back to search with message
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            $_SESSION['flash_message']['id']['no_id_found'] = getMessage('no_id_found');
            header('Location: /management/admin/searchPageUser');
            exit;
        }

        $id = $_GET['id'];
        //if $id failed validate
        if (validateID($id) !== 0) {
            $_SESSION['flash_message']['id']['invalid'] = getMessage('invalid_id');
            header('Location: /management/admin/searchPageUser');
            exit;
        }

        //if $id passed validate
        //delete
        $rowChange = $this->userModel->deleteById($id);
        if ($rowChange == 1) {
            $_SESSION['flash_message']['delete']['success'] = getMessage('delete_success');
        } else {
            $_SESSION['flash_message']['delete']['failed'] = getMessage('delete_failed');
        }

        header('Location: /management/admin/searchPageUser');
        exit;
    }
}