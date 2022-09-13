<?php

interface QueryInterface
{
    function findByEmailAndName($email,$name, $page);
    function deleteById($id);
    function create($data);
    function update($id, $data);
}