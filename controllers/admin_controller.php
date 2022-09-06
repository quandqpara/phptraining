<?php
require_once('controllers/base_controller.php');
require_once('model/admin.php');
require_once('Helper/validation.php');

class adminController extends BaseController
{
    private $email = '';
    private $password = '';

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

        if ($this->validateInput($method, $request)) {
            showLog($_SESSION);
            $this->password = $request['password'];
            $this->email = $request['email'];
            $db = DB::getInstance();
            $stmt = $db->prepare("SELECT id, name, email, avatar, role_type FROM admin WHERE email = :email AND password = :pasword AND del_flag = 0");
            $stmt->bindParam('email', $this->email, PDO::PARAM_STR);
            $stmt->bindParam('password', $this->password, PDO::PARAM_STR);
            $stmt->execute();
        }
    }

    public function validateInput($method, $request)
    {
        $listErr = [];

        if ($method != 'POST') {
            return;
        }

        if (empty($request)) {
            return;
        }

        if (isset($request['email'])) {
            if (validateEmail($request['email'])){
                array_push($listErr,'invalid_email');
            }
        }
        if (isset($request['password'])) {
            if (validatePassword(isset($request['password']))){
                array_push($listErr,'invalid_password');
            }
        }
        return true;

    }
}