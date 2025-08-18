<?php

namespace jbrowneuk;

require_once '../vendor/autoload.php';

require_once './interfaces/ialbumdbo.php';
require_once './interfaces/iauthentication.php';
require_once './interfaces/iauthenticationdbo.php';
require_once './interfaces/ipostsdbo.php';
require_once './interfaces/irenderer.php';

require_once './model/album.php';
require_once './model/image.php';
require_once './model/post.php';

require_once './core/authentication.php';
require_once './core/renderer.php';
require_once './core/routes.php';
require_once './core/url-helpers.php';

require_once './database/album.dbo.php';
require_once './database/authentication.dbo.php';
require_once './database/connect.php';
require_once './database/posts.dbo.php';

require_once './services/github-projects.php';

require_once './di/initialise.php';

require_once './actions/art.php';
require_once './actions/auth.php';
require_once './actions/editor.php';
require_once './actions/error.php';
require_once './actions/journal.php';
require_once './actions/portfolio.php';
require_once './actions/projects.php';
require_once './actions/rss.php';

require_once './config.php';

$pdo = connect($db);
if (!$pdo) {
    die('Could not connect to database.');
}

$container = initialiseContainer($pdo);

// Clean request URI if script directory is defined
$rawUri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
$requestUri = mb_strtolower($rawUri);
if (isset($scriptDirectory) && str_starts_with($requestUri, $scriptDirectory)) {
    $requestUri = substr($requestUri, strlen($scriptDirectory));
}

// Calculate route
$request = getRequestedPage($requestUri, $defaultAction);
if (array_key_exists($request['action'], $routes)) {
    $actionClass = $routes[$request['action']];
} else {
    $actionClass = $routes[$errorAction];
}

// Initialise page renderer
$renderer = new PortfolioRenderer();
$renderer->setStyleRoot(isset($styleRoot) ? $styleRoot : '');
$renderer->setScriptDirectory(isset($scriptDirectory) ? $scriptDirectory : '');

// Calculate pageUrl for pagination
$pageUrl = "/{$request['action']}";
if (isset($scriptDirectory)) {
    $pageUrl = $scriptDirectory . $pageUrl;
}

$renderer->assign('pageUrl', $pageUrl);

$container->set(IRenderer::class, $renderer);

// Render the page
$container->call($actionClass, [$request['params']]);

