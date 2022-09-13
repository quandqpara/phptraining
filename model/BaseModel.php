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
    }

    function writeLog($log){
        $logFile = fopen("log.txt", "w") or die("Unable to open file");
        fwrite($logFile, $log);
        fclose($logFile);
    }

    //helper
    //Autobind:
    //&$stmt that have value that need binding
    //$needBinding array of key & value of corresponding value need in $stmt
    public function autoBind(&$stmt, $needBinding = []){
        foreach ($needBinding as $key=>&$value){
            $stmt->bindParam(":$key", $value, PDO::PARAM_STR);
        }
    }

    //remove empty input field
    public function removeEmptyField(&$arrayOfInput){
        foreach ($arrayOfInput as $key=>$value){
            if(is_null($value) || $value == ''){
                unset($arrayOfInput[$key]);
            }
        }
    }

    //compare with $fillable to remove non-accepted data
    //for each element in &$inputArray
    //if $key is not in fillable array
    //remove that from &$inputArray
    public function checkFillable(&$input){
        foreach ($input as $key=>$value){
            if(!in_array($key, $this->fillable)){
                unset($input[$key]);
            }
        }
    }

    //need 'name', 'password', 'email', 'avatar', 'role_type', 'ins_id', 'ins_datetime'
    public function create( $validatedDataFromInput = [])
    {
        // TODO: Implement create() method.

        $validatedDataFromInput = array_merge($validatedDataFromInput, [
            'ins_id' => getAdminID(),
            'ins_datetime' => date('Y-m-d H:i:s')
        ]);

        $this->checkFillable($validatedDataFromInput);
        //if checkFillable() removed some unnecessary data and that make
        //number of columns that need info is different from number of values provided.
        //do not create new account
        if(count($validatedDataFromInput) != count($this->columnCreate)){
            $_SERVER['flash_message']['create']['failed'] = getMessage('create_failed');
            header('Location: admin/create/');
            exit;
        }
        try {
            $sql = "INSERT INTO {$this->tableName}( {implode(',', $this->columnCreate)},del_flag) VALUES ( :{implode(': ,', $validatedDataFromInput)},".DEL_FLAG_OFF.")";
            $stmt = $this->conn->prepare($sql);
            $this->autoBind($stmt, $validatedDataFromInput);
            $stmt->execute();

            if ($stmt->rowCount() == 0) {
                $_SESSION['flash_message']['create']['failed'] = getMessage('create_failed');
            } else {
                $_SESSION['flash_message']['create']['success'] = getMessage('create_success');
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        $log = "ACTION: Create account at email ".$validatedDataFromInput['email']."- BY: ".$validatedDataFromInput['ins_id']." DATE: ".$validatedDataFromInput['ins_datetime'];
        $this->writeLog($log);
    }

    //need which row to update and its corresponding value and id
    public function update($id, $validatedInput)
    {
        // TODO: Implement create() method.
        $rowChange = 0;
        $data = array_merge($validatedInput, [
            'upd_id' => getAdminID(),
            'upd_datetime' => date('Y-m-d H:i:s')
        ]);

        // check fillable
        $this->removeEmptyField($data);
        $this->checkFillable($data);

        $columnArr = array();
        foreach ($data as $key=>$value){
            $setKeyAndValue = $key.' = :'.$key;
            $columnArr[] = $setKeyAndValue;
        }

        $setArray = implode(', ', $columnArr);

        try {
            $sql = "UPDATE {$this->tableName} SET {$setArray} WHERE id = {$id}";
            $stmt = $this->conn->prepare($sql);
            $this->autoBind($stmt, $data);
            $stmt->execute();

            $rowChange = $stmt->rowCount();
            if ($rowChange == 0) {
                $_SESSION['flash_message']['update']['failed'] = getMessage('update_failed');
            } else {
                $_SESSION['flash_message']['update']['success'] = "Admin ID: ".$id.getMessage('update_success');
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        $log = "ACTION: UPDATE account at id: ".$id." - BY: ".$data['ins_id']." DATE: ".$data['ins_datetime'];
        $this->writeLog($log);

        header('Location: /admin/home?email='.$data['email'].'&name='.$data['name']);
        exit;
    }

    //need email and name
    public function findByEmailAndName($email, $name, $page)
    {
        $dataPackage = [];
        $resultFromSearch = [];
        $paginationInfo = [];

        $data = array(
            'email' => $email,
            'name' => $name,
            'search_id' => getAdminID(),
            'search_datetime' => date('Y-m-d H:i:s')
        );

        $limit = 10;
        $start = $limit * ($page-1);
        $total = 0;

        $sql = "SELECT id, avatar, name, email, role_type 
                FROM {$this->tableName} 
                WHERE email LIKE '%{$email}%'
                    AND name LIKE '%{$name}%'
                    AND del_flag = ".DEL_FLAG_OFF ;

        $limitSQL = " LIMIT ". $start.",".$limit;

        //getting total number of result
        if($total == 0){
            try {
                $stmtUnlimit = $this->conn->prepare($sql);
                $stmtUnlimit->execute();
                $stmtUnlimit->fetchAll(PDO::FETCH_ASSOC);

                $total = $stmtUnlimit->rowCount();

            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }

        //return page with limited result
        try {
            $query = $sql.$limitSQL;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            $resultFromSearch = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $dataPackage['data'] = $resultFromSearch;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        $totalPages = ceil($total/ $limit);
        $page = (isset($page)&&$page<10000) ? (int)$page : 1;
        $start = $limit * ($page - 1);
        $next = ($page > 1) ? $page + 1 : 1;
        $prev = ($page < $total) ? $page - 1 : $total;

        $paginationInfo = [
            'totalPages' => $totalPages,
            'page' => $page,
            'start' => $start,
            'next' => $next,
            'prev' => $prev
        ];
        $dataPackage['pagination'] = $paginationInfo;

        $log = "ACTION: SEARCH email: ".$email." and name: ".$name." - BY: ".$data['search_id']." DATE: ".$data['search_datetime'];
        $this->writeLog($log);

        return $dataPackage;
    }

    //need id
    public function deleteById($id)
    {
        $data = array(
            'id' => $id,
            'upd_id' => getAdminID(),
            'upd_datetime' => date('Y-m-d H:i:s')
        );

        try {
            $sql = "UPDATE {$this->tableName} SET del_flag = ".DEL_FLAG_ON." WHERE id = ".$id;
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

            if ($stmt->rowCount() == 0) {
                $_SESSION['flash_message']['update']['failed'] = getMessage('update_failed');
            } else {
                $_SESSION['flash_message']['update']['success'] = getMessage('update_success');
            }

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        $log = "ACTION: DELETE account at id ".$id." - BY: ".$data['ins_id']." DATE: ".$data['ins_datetime'];
        $this->writeLog($log);
    }
}