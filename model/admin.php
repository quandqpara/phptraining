<?php

class admin
{
    private $email;

    public function __construct ($email){
        $this->email = $email;
    }

    public function getEmail(){
        return $this->email;
    }
}