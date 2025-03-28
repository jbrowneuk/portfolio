<?php
namespace jbrowneuk;

require_once './vendor/autoload.php';
require_once './database/connect.php';

require_once './config.php';

$pdo = \jbrowneuk\connect($db);
if (!$pdo) {
    die('Could not connect to database.');
}

echo 'Done';
