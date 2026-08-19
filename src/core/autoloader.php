<?php

namespace jbrowneuk\core;

spl_autoload_register(function ($fqClassName) {
    $parts = explode('\\', $fqClassName);
    $namespace = array_shift($parts);

    // Only handle autoloading for current site namespace
    if (count($parts) === 0 || $namespace !== 'jbrowneuk') {
        throw new \Exception("Cannot autoload class: " . $fqClassName);
    }

    $className = strtolower(preg_replace('/(?:\d++|[A-Za-z]?[a-z]++)\K(?!$)/', '-', array_pop($parts)));
    require './' . implode('/', $parts) . '/' . $className . '.php';
});
