<?php
include_once  'model/interface/QueryInterface.php';

abstract class BaseModel implements QueryInterface
{
    public $tableName;
    public $fillable;

    public function getAll($fields = [])
    {
        if (empty($fields)) {
            $fields[] = 'id';
        }

        // TODO: Implement getAll() method.
        return "query select {implode($fields) from $this->tableName} where del_flag = " . DELETED_OFF;
    }

    public function create($data)
    {
        // TODO: Implement create() method.

        $data = array_merge($data, [
            'ins_id' => getSessionAdmin('id'),
            'ins_datetime' => date('Y-m-d H:i:s')
        ]);

        // check fillable

        // run exec insert db;
    }

    public function update($id, $data)
    {
        // TODO: Implement create() method.

        $data = array_merge($data, [
            'upd_id' => getSessionAdmin('id'),
            'upd_datetime' => date('Y-m-d H:i:s')
        ]);

        // check fillable

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
}