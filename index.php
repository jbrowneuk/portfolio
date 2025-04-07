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

$Parsedown = new \Parsedown();

function modifier_parsedown($input) {
    global $Parsedown;
    return $Parsedown->text($input);
}

$smarty = new \Smarty\Smarty();
$smarty->setCompileDir('smarty/compile');
$smarty->setCacheDir('smarty/cache');

$smarty->registerPlugin(\Smarty\Smarty::PLUGIN_MODIFIER, 'parsedown', '\jbrowneuk\modifier_parsedown');

// [TODO] get from URL not $_GET to preserve current site behaviour
$requestedAction = isset($_GET['page']) ? $_GET['page'] : $defaultAction;

// Ensure action is alphanumeric
if (preg_match('/[^a-z]/i', $requestedAction)) {
    $requestedAction = $defaultAction;
}

// Check action exists
$actionPath = './actions/' . $requestedAction . '.php';
if (!file_exists($actionPath)) {
    $actionPath = './actions/' . $defaultAction . '.php';
}

require_once($actionPath);
renderAction($pdo, $smarty);