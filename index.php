<?php
namespace jbrowneuk;

require_once './vendor/autoload.php';
require_once './database/connect.php';
require_once './database/posts.php';

require_once './config.php';

$pdo = \jbrowneuk\connect($db);
if (!$pdo) {
    die('Could not connect to database.');
}

try {
    $posts = \jbrowneuk\get_posts($pdo);
} catch (\PDOException $ex) {
    die($ex->getMessage());
}

$smarty = new \Smarty\Smarty();
$smarty->setCompileDir('smarty/compile');
$smarty->setCacheDir('smarty/cache');

$smarty->assign('posts', $posts);
$smarty->display('post-list.tpl');

