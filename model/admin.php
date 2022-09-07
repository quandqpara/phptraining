<?php

class Admin
{
    function __construct (){

    }

    static function getEmail(){
    }

    static function admin_login($email, $password){
        $user_data = [];

        try {
            $db = DB::getInstance();
            $stmt = $db->prepare("SELECT id, name, email, avatar, role_type FROM admin WHERE email = :email AND password = :password AND del_flag = 0");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->execute();

            $user_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        return $user_data;
    }

}