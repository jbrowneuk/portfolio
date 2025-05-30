<?php

namespace jbrowneuk;

require_once 'src/core/url-helpers.php';

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
