<?php

include_once("model/BaseModel.php");

class AdminModel extends BaseModel
{
    public $fillable = [
        'id',
        'name',
        'del_flag',
        'ins_id',
        'ins_datetime',
        'upd_id',
        'upd_datetime'
    ];

    function __construct()
    {
        $this->tableName = 'admin_table';
    }

    static function admin_login($email, $password)
    {
        $user_data = [];

        try {
            $db = DB::getInstance();
            $stmt = $db->prepare("SELECT id, name, email, avatar, role_type FROM admin WHERE email = :email AND password = :password AND del_flag = :flag");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $flag = DEL_FLAG_OFF;
            $stmt->bindParam(':flag', $flag);
            $stmt->execute();

            $user_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        return $user_data;
    }

}