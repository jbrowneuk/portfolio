<?php

namespace jbrowneuk;

require_once '../vendor/autoload.php';
require_once './core/autoloader.php';

require_once './config.php';
require_once './routes.php';

$pdo = \jbrowneuk\database\Database::connect($db);
if (!$pdo) {
    die('Could not connect to database.');
}

$container = \jbrowneuk\di\ContainerFactory::initialiseContainer($pdo);

// Clean request URI if script directory is defined
$rawUri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
$requestUri = mb_strtolower($rawUri);
if (isset($scriptDirectory) && str_starts_with($requestUri, $scriptDirectory)) {
    $requestUri = substr($requestUri, strlen($scriptDirectory));
}

// Calculate route
$request = \jbrowneuk\core\UrlHelpers::getRequestedPage($requestUri, $DEFAULT_ACTION);
if (array_key_exists($request['action'], $routes)) {
    $actionClass = $routes[$request['action']];
} else {
    $actionClass = $routes[$ERROR_ACTION];
}

// Initialise page renderer
$renderer = new \jbrowneuk\core\PortfolioRenderer();
$renderer->setStyleRoot(isset($styleRoot) ? $styleRoot : '');
$renderer->setScriptDirectory(isset($scriptDirectory) ? $scriptDirectory : '');

// Calculate pageUrl for pagination
$pageUrl = "/{$request['action']}";
if (isset($scriptDirectory)) {
    $pageUrl = $scriptDirectory . $pageUrl;
}

$renderer->assign('pageUrl', $pageUrl);

$container->set(\jbrowneuk\interfaces\IRenderer::class, $renderer);

// Render the page
$container->call($actionClass, [$request['params']]);

