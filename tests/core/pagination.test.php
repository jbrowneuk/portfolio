<?php

namespace jbrowneuk;

require_once 'src/core/pagination.php';

describe('parsePageNumber', function () {
    it('should return page number if array contains \'page\' as element n and number as n+1', function () {
        $expectedPage = 4;
        $params = ['page-name', 'page', $expectedPage, 'other', 'param'];

        $actual = parsePageNumber($params);

        expect($actual)->toBe($expectedPage);
    });

    it('should return default page if provided page number is not numeric', function () {
        $params = ['page-name', 'page', 'not-a-number', 'other', 'param'];

        $actual = parsePageNumber($params);

        expect($actual)->toBe(1);
    });

    it('should return default page if no page number provided', function () {
        $params = ['page-name', 'page'];

        $actual = parsePageNumber($params);

        expect($actual)->toBe(1);
    });
});
