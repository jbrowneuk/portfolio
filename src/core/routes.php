<?php

namespace jbrowneuk;

$errorAction = 'error';

// Page routes
$routes = [
    'art' => Art::class,
    'auth' => Auth::class,
    'editor' => Editor::class,
    'journal' => Journal::class,
    'portfolio' => Portfolio::class,
    'projects' => Projects::class,
    'rss' => RSS::class,
    $errorAction => Error::class
];