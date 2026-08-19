<?php

namespace jbrowneuk\core;

$ERROR_ACTION = 'error';

// Page routes
$routes = [
    'portfolio' => \jbrowneuk\actions\Portfolio::class,
    'art' => \jbrowneuk\actions\Art::class,
    'auth' => \jbrowneuk\actions\Auth::class,
    'editor' => \jbrowneuk\actions\Editor::class,
    'journal' => \jbrowneuk\actions\Journal::class,
    'projects' => \jbrowneuk\actions\Projects::class,
    'rss' => \jbrowneuk\actions\RSS::class,
    $ERROR_ACTION => \jbrowneuk\actions\Error::class
];

$DEFAULT_ACTION = array_key_first($routes);