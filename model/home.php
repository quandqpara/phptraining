<?php
class home{

    private $email;
    private $password;

    function __construct()
    {

    }

    private function getLoginInfo(){
        $this->email = $_POST['email'];
        $this->password = $_POST['password'];
    }

    static function checkLogin()
    {
        $list = [];
        $db = DB::getInstance();
        $req = $db->query('SELECT * FROM admin');

        foreach ($req->fetchAll() as $item){
            $list[] = new user($item['email'], $item['password']);
        }


    }
}