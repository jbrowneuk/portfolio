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

// Calculate action if provided
if (isset($_SERVER['REQUEST_URI'])) {
    $requestUri = $_SERVER['REQUEST_URI'];

    // Drop subdirectory if it is in the request URI
    if (isset($scriptDirectory) && str_starts_with($requestUri, $scriptDirectory)) {
        $requestUri = substr($requestUri, strlen($scriptDirectory));
    }

    $pageParams = array_filter(explode('/', $requestUri));
    $detectedAction = array_shift($pageParams);
    $requestedAction = isset($detectedAction) ? $detectedAction : $defaultAction;
} else {
    $requestedAction = $defaultAction;
}

// Ensure action is alphanumeric
if (preg_match('/[^a-z]/i', $requestedAction)) {
    $requestedAction = $defaultAction;
}

// Check action exists
$rootDir = dirname(__FILE__);
$actionPath = $rootDir . '/actions/' . $requestedAction . '.php';
if (!file_exists($actionPath)) {
    $actionPath = $rootDir . '/actions/' . $defaultAction . '.php';
}

require_once($actionPath);
renderAction($pdo, $renderer);