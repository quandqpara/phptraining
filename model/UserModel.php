<?php
class UserModel extends BaseModel {

    private $facebookID = '';

    public function __construct (){
        $this->tableName = 'user';
        $this->fillable = [
            'id',
            'name',
            'avatar',
            'email',
            'password',
            'status',
            'del_flag',
            'ins_id',
            'ins_datetime',
            'upd_id',
            'upd_datetime'
        ];
        $this->columnCreate = array('name', 'password', 'email', 'avatar', 'status', 'ins_id', 'ins_datetime');
        $this->conn = DB::getInstance();
    }

    public function facebookAuth(){}

    public function login(){}

    public function searchOneUser($id)
    {
        $targetInfo = [];
        try {
            $stmt = $this->conn->prepare("SELECT name, email, avatar FROM ".$this->tableName." WHERE id = :id AND del_flag = :flag");
            $stmt->bindParam(':id', $_GET['id']);
            $flag = DEL_FLAG_OFF;
            $stmt->bindParam(':flag', $flag);
            $stmt->execute();

            $targetInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: ". $e;
        }

        return $targetInfo;
    }
}
