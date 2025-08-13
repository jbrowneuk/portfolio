<?php

namespace jbrowneuk;

require_once 'src/core/url-helpers.php';

describe('getRequestedPage', function () {
    it('should return default action and empty params if path is empty', function () {
        $path = '';
        $defaultAction = 'potato';

        $result = getRequestedPage($path, $defaultAction);

        expect($result['action'])->toBe($defaultAction);
        expect($result['params'])->toBe([]);
    });

    it('should return action and empty params if path contains single element', function () {
        $path = 'roast';
        $defaultAction = 'potato';

        $result = getRequestedPage($path, $defaultAction);

        expect($result['action'])->toBe($path);
        expect($result['params'])->toBe([]);
    });

    it('should return action and parsed params if path contains multiple elements', function () {
        $path = 'roast/fried/boiled/baked';
        $defaultAction = 'potato';
        $pathBits = explode('/', $path);

        $result = getRequestedPage($path, $defaultAction);

        expect($result['action'])->toBe(array_shift($pathBits));
        expect($result['params'])->toBe($pathBits);
    });
});

describe('getValueFromPageParams', function () {
    it('should return value if array contains \'key\' as element n and a value as n+1', function () {
        $expectedKey = 'food';
        $expectedValue = 'potato';
        $params = ['page-name', $expectedKey, $expectedValue, 'other', 'param'];

        $actual = getValueFromPageParams($params, $expectedKey);

        expect($actual)->toBe($expectedValue);
    });

    it('should return null if no value after key', function () {
        $expectedKey = 'food';
        $params = ['page-name', $expectedKey];

        $actual = getValueFromPageParams($params, $expectedKey);

        expect($actual)->toBeNull();
    });

    it('should return null if item not found in array', function () {
        $keyToFind = 'tag';
        $params = ['page', '3'];

        $actual = getValueFromPageParams($params, $keyToFind);

        expect($actual)->toBeNull();
    });
});

describe('parsePageNumber', function () {
    it('should return page number if array contains \'page\' as element n and number as n+1', function () {
        $expectedPage = 4;
        $params = ['page-name', 'page', $expectedPage, 'other', 'param'];

        $actual = parsePageNumber($params);

        expect($actual)->toBe($expectedPage);
    });

    it('should return default page if provided page number in params is not numeric', function () {
        $params = ['page-name', 'page', 'not-a-number', 'other', 'param'];

        $actual = parsePageNumber($params);

        expect($actual)->toBe(1);
    });

    it('should return default page if no page number provided in params', function () {
        $params = ['page-name', 'page'];

        $actual = parsePageNumber($params);

        expect($actual)->toBe(1);
    });
});
