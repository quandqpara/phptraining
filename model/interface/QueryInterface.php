<?php

interface QueryInterface
{
    function findByEmailAndName($email,$name);
    function deleteById($id);
    function create($data);
    function update($id, $data);
}