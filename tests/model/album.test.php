<?php

namespace jbrowneuk;

describe('Album object', function () {
    it('should set properties correctly on creation', function () {
        $album = new Album(MOCK_ALBUM_1_ROW);
        expect($album->id)->toBe(MOCK_ALBUM_1_ROW['album_id']);
        expect($album->name)->toBe(MOCK_ALBUM_1_ROW['name']);
        expect($album->description)->toBe(MOCK_ALBUM_1_ROW['description']);
    });

    it('should set imageCount property correctly', function () {
        $album = new Album(MOCK_ALBUM_1_ROW);
        $expectedCount = 512;

        $album->setImageCount($expectedCount);

        expect($album->imageCount)->toBe($expectedCount);
    });
});
