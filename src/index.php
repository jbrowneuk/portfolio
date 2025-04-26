<?php
namespace jbrowneuk;

// Vendor
require_once '../vendor/autoload.php';

require_once './core/page.php';
require_once './core/renderer.php';

require_once './database/connect.php';
require_once './database/posts.php';

require_once './actions/art.php';
require_once './actions/journal.php';
require_once './actions/portfolio.php';
require_once './actions/projects.php';

require_once './config.php';

$pdo = connect($db);
if (!$pdo) {
    die('Could not connect to database.');
}

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
    $pageParams = [];
    $requestedAction = $defaultAction;
}

$routes = [
    'portfolio' => Portfolio::class,
    'art' => Art::class,
    'journal' => Journal::class,
    'projects' => Projects::class
];

if (array_key_exists($requestedAction, $routes)) {
    $actionClass = $routes[$requestedAction];
} else {
    $actionClass = $routes[$defaultAction];
}

$action = new $actionClass();
$action->render($pdo, new PortfolioRenderer(), $pageParams);