<?php
require_once('controllers/base_controller.php');

class errorController extends BaseController
{
    public function __construct()
    {
        $this->folder = 'error';
    }

    public function error()
    {
        return $this->render('error');
    }
}