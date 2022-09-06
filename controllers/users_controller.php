<?php
require_once('controllers/base_controller.php');

class usersController extends BaseController
{
    public function __construct()
    {
        $this->folder = 'user';
    }

    public function index()
    {
        return $this->render('index');
    }

    public function home()
    {
        return $this->render('home');
    }


    public function auth(){

    }

    public function fbAuth(){

    }

    private function fbCallback(){

    }
}