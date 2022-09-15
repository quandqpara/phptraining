<?php

include_once("model/BaseModel.php");

class AdminModel extends BaseModel
{
    function __construct()
    {
        $this->tableName = 'admin';
        $this->fillable = [
            'id',
            'name',
            'avatar',
            'email',
            'password',
            'role_type',
            'del_flag',
            'ins_id',
            'ins_datetime',
            'upd_id',
            'upd_datetime'
        ];
        $this->columnCreate = array('name', 'password', 'email', 'avatar', 'role_type', 'ins_id', 'ins_datetime');
        $this->conn = DB::getInstance();
        $this->loginArrayInfo = "id, name, email, avatar, role_type";
    }

    public function searchOneAdmin($id)
    {
        $targetInfo = [];
        try {
            $stmt = $this->conn->prepare("SELECT name, email, avatar FROM " . $this->tableName . " WHERE id = :id AND del_flag = :flag");
            $stmt->bindParam(':id', $_GET['id']);
            $flag = DEL_FLAG_OFF;
            $stmt->bindParam(':flag', $flag);
            $stmt->execute();

            $targetInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e;
        }

        return $targetInfo;
    }
}