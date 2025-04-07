<?php
namespace jbrowneuk;

require_once './vendor/autoload.php';
require_once './database/connect.php';
require_once './database/posts.php';
require_once './core/renderer.php';

require_once './config.php';

$pdo = \jbrowneuk\connect($db);
if (!$pdo) {
    die('Could not connect to database.');
}

$renderer = new PortfolioRenderer();

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
renderAction($pdo, $renderer);