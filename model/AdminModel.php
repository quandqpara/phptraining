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
}