<?php

namespace jbrowneuk;

describe('Image object', function () {
    it('should set properties correctly on creation', function () {
        $image = new Image(MOCK_IMAGE_HORIZ_ROW);
        expect($image->id)->toBe(MOCK_IMAGE_HORIZ_ROW['image_id']);
        expect($image->title)->toBe(MOCK_IMAGE_HORIZ_ROW['title']);
        expect($image->filename)->toBe(MOCK_IMAGE_HORIZ_ROW['filename']);
        expect($image->description)->toBe(MOCK_IMAGE_HORIZ_ROW['description']);
        expect($image->timestamp)->toBe(MOCK_IMAGE_HORIZ_ROW['timestamp']);
        expect($image->width)->toBe(MOCK_IMAGE_HORIZ_ROW['width']);
        expect($image->height)->toBe(MOCK_IMAGE_HORIZ_ROW['height']);
    });

    it('should set albums property correctly', function () {
        $image = new Image(MOCK_IMAGE_HORIZ_ROW);
        $albums = [
            new Album(MOCK_ALBUM_1_ROW),
            new Album(MOCK_ALBUM_2_ROW)
        ];

        $image->setAlbums($albums);

        expect($image->albums)->toEqual($albums);
    });

    it('should promote to featured', function () {
        $image = new Image(MOCK_IMAGE_HORIZ_ROW);

        expect($image->featured)->toBeFalse();

        $image->setFeatured();

        expect($image->featured)->toBeTrue();
    });
});
