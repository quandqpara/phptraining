<?php
include_once("model/BaseModel.php");

class UserModel extends BaseModel
{

    private $facebookID = '';

    public function __construct()
    {
        $this->tableName = 'user';
        $this->fillable = [
            'id',
            'name',
            'facebook_id',
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
        $this->columnCreate = array('name', 'facebook_id', 'email', 'avatar', 'ins_id', 'ins_datetime');
        $this->conn = DB::getInstance();
        $this->loginArrayInfo = "id, name, email, avatar, status";
    }

    public function createUserWithInfoFromFacebook($validatedDataFromInput = [])
    {
        $data = array_merge($validatedDataFromInput, [
            'ins_id' => DEFAULT_INS_ID,
            'ins_datetime' => date('Y-m-d H:i:s')
        ]);

        //if user is already exist (stop creating new user)
        //redirect to user homepage
        //$userExist contains all basic info of the user
        $userExist = $this->checkExistenceByFacebookId($data['facebook_id']);
        if (!empty($userExist)) {
            return $userExist;
        }

        //if checkFillable() removed some unnecessary data and that make
        //the amount of data from input more or less than the number of column require input.
        //do not create new account
        $this->checkFillable($data);
        if (count($data) != count($this->columnCreate)) {
            return null;
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
            if ($rowCount == 0 || $rowCount > 1) {
                $_SESSION['flash_message']['create']['failed'] = getMessage('create_failed');
                return null;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        $log = "ACTION: Create account at email " . $data['email'] . "- BY: " . $data['ins_id'] . " DATE: " . $data['ins_datetime'];
        writeLog($log);

        //by now the new user is created => return an array of basic info to display on user homepage
        return $this->checkExistenceByFacebookId($data['facebook_id']);
    }

    //Existence Check
    public function checkExistenceByFacebookId($facebookId)
    {
        try {
            $stmt = $this->conn->prepare("SELECT id, name, avatar, email FROM " . $this->tableName . " WHERE facebook_id = :facebook_id AND del_flag = :flag");
            $stmt->bindParam(':facebook_id', $facebookId);
            $flag = DEL_FLAG_OFF;
            $stmt->bindParam(':flag', $flag);
            $stmt->execute();

            $targetInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e;
        }
        return $targetInfo;
    }

    public function basicLogin($email, $password)
    {
        $userData = [];

        try {
            if(empty($password)){
                $query = "SELECT {$this->loginArrayInfo} FROM ".$this->tableName." WHERE email = :email AND del_flag = ".DEL_FLAG_OFF;
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':email', $email);
            } else {
                $query = "SELECT {$this->loginArrayInfo} FROM ".$this->tableName." WHERE email = :email AND password = :password AND del_flag = ".DEL_FLAG_OFF;
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $password);
            }

            $stmt->execute();
            $userData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        return $userData;
    }
}
