<?php

namespace jbrowneuk;

/**
 * Attempts to parse the requested page and parameters from a path-like URI.
 * Returns the parsed information as an associative array. If the path cannot be
 * parsed, the provided default action will be returned with an empty parameter
 * array.
 * 
 * - `action`: the action is the first segment of the path.
 * - `params`: the params are generated from the remainder of the path, split on
 *             the slash character.
 * 
 * Considering the following path, `/my-action/param/goes/here`, this function
 * will return the following:
 * - the action value will be `my-action`
 * - the params value will be ['param', 'goes', 'here']
 *
 * @param string $path a path-like string
 * @param string $defaultAction the default action to return
 *
 * @return array an array containing two keys, `action` and `params` which
 *               represents the input path
 */
function getRequestedPage(string $path, string $defaultAction): array
{
    $page = ['action' => $defaultAction, 'params' => []];

    $pageParams = array_filter(explode('/', $path));
    $detectedAction = array_shift($pageParams);
    $page['action'] = isset($detectedAction) ? $detectedAction : $defaultAction;
    $page['params'] = $pageParams;

    return $page;
}

/**
 * Attempts to parse a value from a key/value pair provided as a set of parameters.
 *
 * Considering a URL string split on slash, this function will try to find a
 * pair in the form [$key, <value>] and return the value.
 *
 * i.e. `/path/to/my-key/my-value/etc` should return `my-value` when looking for
 * the key `my-key`.
 *
 * @param array $pageParams an array of strings, usually generated by splitting
 *              the REQUEST URI on slash
 *
 * @return int the parsed page number
 */
function getValueFromPageParams(array $pageParams, string $key): string | null
{
    $index = array_find_key($pageParams, fn($item) => $item === $key);
    if (!array_key_exists($index + 1, $pageParams)) {
        return null;
    }

    return $pageParams[$index + 1];
}

/**
 * Attempts to parse the page number from a provided set of parameters.
 *
 * Considering a URL string split on slash, this function will try to find a
 * pair in the form ['page', <number>] and return the number.
 *
 * i.e. `/path/to/page/5` should return `5`
 *
 * @param array $pageParams an array of strings, usually generated by splitting
 *              the REQUEST URI on slash
 *
 * @return int the parsed page number
 */
function parsePageNumber(array $pageParams): int
{
    $page = getValueFromPageParams($pageParams, 'page');
    if (is_numeric($page)) {
        return (int)$page;
    }

    return 1;
}
