<?php
include_once  'model/interface/QueryInterface.php';

abstract class BaseModel implements QueryInterface
{
    public $tableName;
    public $fillable;
    public $conn;
    public $columnCreate;
    public $columnUpdate;


    function __construct(){
        $this->conn = DB::getInstance();
    }

    public function getAll($fields = [])
    {
        if (empty($fields)) {
            $fields[] = 'id';
        }

        $sql = "SELECT {implode(',', $fields)} FROM {$this->tableName} where del_flag = " . DEL_FLAG_OFF;
        return $sql;
    }

    //helper
    public function autoBind(&$stmt, $needBinding = []){
        foreach ($needBinding as $item){
            $stmt->bindParam(':'.array_search($item).'', $item);
        }
    }

    //compare with $fillable to remove non-accepted data
    public function checkFillable($input){
        foreach ($input as $item){
            if(!in_array(array_search($item), $this->fillable)){
                unset($input[array_search($item)]);
            }
        }
    }

    public function create( $validatedDataFromInput = [])
    {
        // TODO: Implement create() method.

        $validatedDataFromInput = array_merge($validatedDataFromInput, [
            'ins_id' => getAdminID(),
            'ins_datetime' => date('Y-m-d H:i:s')
        ]);

        $this->checkFillable();
        //if checkFillable() removed some unnecessary data and that make
        //number of columns that need info is different from number of values provided.
        //do not create new account
        if(count($validatedDataFromInput) != count($this->columnCreate)){
            $_SERVER['flash_message']['create']['failed'] = getMessage('create_failed');
            header('Location: admin/create/');
            exit;
        }

        $sql = "INSERT INTO {$this->tableName}( {implode(',', $this->columnCreate)} ) VALUES ( :{implode(': ,', $validatedDataFromInput)} )";
        $stmt = $this->conn->prepare($sql);
        $this->autoBind($stmt, $validatedDataFromInput);
        $stmt->execute();

        if($stmt->rowCount() == 0){
            $_SESSION['flash_message']['create']['failed'] = getMessage('create_failed');
        } else {
            $_SESSION['flash_message']['create']['success'] = getMessage('create_success');
        }
    }

    public function update($id, $validatedInput)
    {
        // TODO: Implement create() method.

        $data = array_merge($validatedInput, [
            'upd_id' => getAdminID(),
            'upd_datetime' => date('Y-m-d H:i:s')
        ]);

        // check fillable
        $this->checkFillable();
        //if checkFillable() removed some unnecessary data and that make
        //number of columns that need info is different from number of values provided.
        //do not create new account
        if(count($validatedInput) != count($this->columnUpdate)){
            $_SERVER['flash_message']['update']['failed'] = getMessage('update_failed');
            header('Location: admin/update/');
            exit;
        }

        // run exec insert db;
    }

    public function findById($id)
    {
        // TODO: Implement findById() method.
    }

    public function deleteById($id)
    {
        // TODO: Implement deleteById() method.
    }

    public function runQuery($query, $conn, $data){
        $stmt = $this->conn->prepare($query);
        $dataStr = explode($data);

    }


}