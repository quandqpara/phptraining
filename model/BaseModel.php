<?php
include_once 'model/interface/QueryInterface.php';

abstract class BaseModel implements QueryInterface
{
    public $tableName;
    public $fillable;
    public $conn;
    public $columnCreate;
    public $loginArrayInfo;

    function __construct()
    {
    }

    //--------------------------------------------------HELPER----------------------------------------------------------
    //Autobind:
    //&$stmt that have value that need binding
    //$needBinding array of key & value of corresponding value need in $stmt
    public function autoBind(&$stmt, $needBinding = [])
    {
        foreach ($needBinding as $key => &$value) {
            $stmt->bindParam(":$key", $value, PDO::PARAM_STR);
        }
    }

    //compare with $fillable to remove non-accepted data
    //for each element in &$inputArray
    //if $key is not in fillable array
    //remove that from &$inputArray
    public function checkFillable(&$input)
    {
        foreach ($input as $key => $value) {
            if (!in_array($key, $this->fillable)) {
                unset($input[$key]);
            }
        }
    }

    //set Array of SELECT items
    public function setSelectItems($target)
    {
        $select = '';
        if ($target == 'admin') {
            $select = 'id, avatar, name, email, role_type';
        } elseif ($target == 'user') {
            $select = 'id, avatar, name, email, status';
        }
        return $select;
    }

    //--------------------------------------------------Login-----------------------------------------------------------
    public function basicLogin($email, $password)
    {
        $userData = [];

        try {
            $query = "SELECT {$this->loginArrayInfo} FROM " . $this->tableName . " WHERE email = :email AND password = :password AND del_flag = " . DEL_FLAG_OFF;
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->execute();
            $userData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        return $userData;
    }

    //-------------------------------------------------DB---------------------------------------------------------------
    //need 'name', 'password', 'email', 'avatar', 'role_type', 'ins_id', 'ins_datetime'
    public function create($validatedDataFromInput = [])
    {
        // TODO: Implement create() method.

        $data = array_merge($validatedDataFromInput, [
            'ins_id' => getAdminID(),
            'ins_datetime' => date('Y-m-d H:i:s')
        ]);

        //if checkFillable() removed some unnecessary data and that make
        //the amount of data from input more or less than the number of column require input.
        //do not create new account
        $this->checkFillable($data);
        if (count($data) != count($this->columnCreate)) {
            $_SESSION['flash_message']['create']['failed'] = getMessage('create_failed');
            header('Location: admin/createAdmin/');
            exit;
        }

        $listOfRequireInfo = implode(', ', $this->columnCreate);
        $listOfRequireValue = ':' . implode(', :', $this->columnCreate);

        $rowCount = 0;
        try {
            $sql = "INSERT INTO {$this->tableName} ({$listOfRequireInfo}) VALUES ({$listOfRequireValue})";
            $stmt = $this->conn->prepare($sql);
            $this->autoBind($stmt, $data);
            $stmt->execute();

            $rowCount = $stmt->rowCount();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        $log = "ACTION: Create account at email " . $data['email'] . "- BY: " . $data['ins_id'] . " DATE: " . $data['ins_datetime'];
        writeLog($log);

        return $rowCount;
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
        $this->checkFillable($data);

        $columnArr = array();
        foreach ($data as $key => $value) {
            $setKeyAndValue = $key . ' = :' . $key;
            $columnArr[] = $setKeyAndValue;
        }

        $setArray = implode(', ', $columnArr);
        $rowChange = 0;
        try {
            $sql = "UPDATE {$this->tableName} SET {$setArray} WHERE id = {$id}";
            $stmt = $this->conn->prepare($sql);
            $this->autoBind($stmt, $data);
            $stmt->execute();

            $rowChange = $stmt->rowCount();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        $log = "ACTION: UPDATE account at id: " . $id . " - BY: " . $data['ins_id'] . " DATE: " . $data['ins_datetime'];
        writeLog($log);

        return $rowChange;
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
        $total = 0;

        $countQuery = "SELECT count(id) 
                FROM {$this->tableName} 
                WHERE email LIKE '%{$email}%'
                    AND name LIKE '%{$name}%'
                    AND del_flag = " . DEL_FLAG_OFF;

        //getting total number of result
        if ($total == 0) {
            try {
                $stmtUnlimit = $this->conn->prepare($countQuery);
                $stmtUnlimit->execute();
                $result = $stmtUnlimit->fetch(PDO::FETCH_ASSOC);
                $total = $result['count(id)'];
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }

        //return page with limited result
        $selectThis = $this->setSelectItems($this->tableName);
        try {
            $query = "SELECT " . $selectThis . "
                FROM {$this->tableName} 
                WHERE email LIKE '%{$email}%'
                    AND name LIKE '%{$name}%'
                    AND del_flag = " . DEL_FLAG_OFF;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            $resultFromSearch = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $dataPackage['data'] = $resultFromSearch;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        $totalPages = ceil($total / $limit);
        $page = (isset($page) && $page < 10000) ? (int)$page : 1;
        $start = $limit * ($page - 1);
        $next = ($page > 1) ? $page + 1 : 1;
        $prev = ($page < $total) ? $page - 1 : $total;
        if ($prev == 0) {
            $prev = 1;
        }

        $paginationInfo = [
            'totalPages' => $totalPages,
            'page' => $page,
            'start' => $start,
            'next' => $next,
            'prev' => $prev
        ];
        $dataPackage['pagination'] = $paginationInfo;

        $log = "ACTION: SEARCH email: " . $email . " and name: " . $name . " - BY: " . $data['search_id'] . " DATE: " . $data['search_datetime'];
        writeLog($log);

        return $dataPackage;
    }

    //need id
    public function deleteById($id)
    {
        $data = array(
            'del_flag' => DEL_FLAG_ON,
            'upd_id' => getAdminID(),
            'upd_datetime' => date('Y-m-d H:i:s')
        );

        $rowChange = $this->update($id, $data);
        $log = "ACTION: DELETE account at id " . $id . " - BY: " . $data['ins_id'] . " DATE: " . $data['ins_datetime'];
        writeLog($log);
        return $rowChange;
    }

    //------------------------------------------------------------------------------------------------------------------
    public function searchOneByID($id)
    {
        $targetInfo = [];
        try {
            $stmt = $this->conn->prepare("SELECT name, email, avatar FROM " . $this->tableName . " WHERE id = :id AND del_flag = " . DEL_FLAG_OFF);
            $stmt->bindParam(':id', $_GET['id']);
            $stmt->execute();

            $targetInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e;
        }

        return $targetInfo;
    }
}