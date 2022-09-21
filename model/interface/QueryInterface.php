<?php

interface QueryInterface
{
    function findByEmailAndName($email,$name, $page, $column, $direction);
    function deleteById($id);
    function create($data);
    function update($id, $data);
}