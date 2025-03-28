<?php
namespace jbrowneuk;

function connect($db) {
    try {
        return new \PDO("sqlite:$db");
    } catch (\PDOException $ex) {
        echo $ex->getMessage();
    }

    return null;
}