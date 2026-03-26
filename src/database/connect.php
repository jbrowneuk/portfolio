<?php

namespace jbrowneuk;

final class Database
{
    public static function connect($db): ?\PDO
    {
        try {
            return new \PDO("sqlite:$db");
        } catch (\PDOException $ex) {
            echo $ex->getMessage();
        }

        return null;
    }
}

function connect($db)
{
    return Database::connect($db);
}
