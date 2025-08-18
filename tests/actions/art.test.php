<?php

namespace jbrowneuk;

require_once 'tests/mocks/art.mock.php';

require_once 'src/interfaces/ialbumdbo.php';
require_once 'src/interfaces/irenderer.php';

require_once 'src/actions/art.php';

class ArtSubActions
{
    const Default = 'default';
    const Album = 'album';
    const AlbumList = 'albums';
    const Image = 'view';
}

describe('Art Action', function () {
    function mockAlbumDbo()
    {
        $mock = \Mockery::mock(IAlbumDBO::class);
        $mock->shouldReceive('getAlbum')->andReturn(MOCK_ALBUM_1);
        $mock->shouldReceive('getAlbums')->andReturn([MOCK_ALBUM_1, MOCK_ALBUM_2]);
        $mock->shouldReceive('getAlbumPaginationData')->andReturn([
            'items_per_page' => MOCK_PER_PAGE,
            'total_items' => MOCK_IMAGE_COUNT
        ]);
        $mock->shouldReceive('getImagesForAlbum')->andReturn([MOCK_IMAGE_HORIZ, MOCK_IMAGE_VERT]);
        $mock->shouldReceive('getImage')->andReturn(MOCK_IMAGE_HORIZ);
        $mock->shouldReceive('getAlbumsForImage')->andReturn([MOCK_ALBUM_1]);

        return $mock;
    }

    beforeEach(function () {
        $this->mockAlbumDbo = mockAlbumDbo();

        $this->assignCalls = array();
        $this->mockRenderer = \Mockery::spy(IRenderer::class);
        $this->mockRenderer->shouldReceive('assign')->andReturnUsing(function ($key, $val) {
            $this->assignCalls[$key] = $val;
        });

        $this->action = new Art($this->mockAlbumDbo, $this->mockRenderer);
    });

    afterEach(function () {
        \Mockery::close();
    });

    describe('albumNameFormatter', function () {
        it('should return concatenated album names separated by comma from input data', function () {
            $input = [MOCK_ALBUM_1, MOCK_ALBUM_2];
            $expected = implode(', ', array_map(fn($item) => $item->name, $input));
            $result = Art::albumNameFormatter($input);
            expect($result)->toBe($expected);
        });
    });

    describe('Shared page rendering', function () {
        beforeEach(function () {
            ($this->action)([ArtSubActions::Default]);
        });

        it('should set page id', function () {
            $this->mockRenderer->shouldHaveReceived('setPageId')->with('art');
        });

        it('should set imageRoot variable', function () {
            expect($this->assignCalls['imageRoot'])->toBe('/media/art/');
        });

        it('should set thumbDir variable', function () {
            expect($this->assignCalls['thumbDir'])->toBe('thumbnails/');
        });

        it('should set imageDir variable', function () {
            expect($this->assignCalls['imageDir'])->toBe('images/');
        });
    });

    describe('Album page behaviour', function () {
        beforeEach(function () {
            ($this->action)([ArtSubActions::Album]);
        });

        it('should display page on template', function () {
            $this->mockRenderer->shouldHaveReceived('displayPage')->with('album');
        });

        it('should assign album data', function () {
            expect($this->assignCalls['album'])->toBe(MOCK_ALBUM_1);
        });

        it('should assign promoted image index', function () {
            // Set by RNG based on album name etc.
            // [TODO] write tests for checking this logic
            expect($this->assignCalls['promotedImageIndex'])->toBeInt();
        });

        it('should assign images', function () {
            expect($this->assignCalls['images'])->toBe([MOCK_IMAGE_HORIZ, MOCK_IMAGE_VERT]);
        });

        it('should assign total image count', function () {
            expect($this->assignCalls['totalImageCount'])->toBe(MOCK_IMAGE_COUNT);
        });

        it('should assign pagination data', function () {
            $result = $this->assignCalls['pagination'];

            expect($result['page'])->toBe(1); // Expected default page
            expect($result['prefix'])->toBe("/album/" . MOCK_ALBUM_1_ROW['album_id']);
            expect($result['items_per_page'])->toBe(MOCK_PER_PAGE);
            expect($result['total_items'])->toBe(MOCK_IMAGE_COUNT);
        });
    });

    describe('Image page behaviour', function () {
        it('should display page without assigning image data if image id not provided', function () {
            ($this->action)([ArtSubActions::Image]);
            expect(array_key_exists('image', $this->assignCalls))->toBeFalse();
        });

        it('should assign image data if image id provided', function () {
            ($this->action)([ArtSubActions::Image, 569]);
            expect($this->assignCalls['image'])->toBe(MOCK_IMAGE_HORIZ);
        });

        it('should display page on template', function () {
            ($this->action)([ArtSubActions::Image]);
            $this->mockRenderer->shouldHaveReceived('displayPage')->with('image');
        });
    });

    describe('Album list behaviour', function () {
        beforeEach(function () {
            ($this->action)([ArtSubActions::AlbumList]);
        });

        it('should assign album data', function () {
            expect($this->assignCalls['albums'])->toBe([MOCK_ALBUM_1, MOCK_ALBUM_2]);
        });

        it('should display page on template', function () {
            $this->mockRenderer->shouldHaveReceived('displayPage')->with('album-list');
        });
    });
});
