<?php

namespace jbrowneuk;

const MOCK_PER_PAGE = 5;
const MOCK_IMAGE_COUNT = 1024;
const MOCK_ALBUM_1 = ['album_id' => 'id1', 'name' => 'a1', 'description' => 'desc1'];
const MOCK_ALBUM_2 = ['album_id' => 'id2', 'name' => 'a2', 'description' => 'desc2'];
const MOCK_IMAGE_HORIZ = [
    'image_id' => 1,
    'title' => 'i',
    'filename' => '1.jpg',
    'description' => 'd1',
    'timestamp' => 0,
    'width' => 100,
    'height' => 50
];
const MOCK_IMAGE_VERT = [
    'image_id' => 2,
    'title' => '2',
    'filename' => '2.jpg',
    'description' => 'd2',
    'timestamp' => 0,
    'width' => 50,
    'height' => 100
];

require_once 'src/interfaces/iaction.php';
require_once 'src/interfaces/ialbumdbo.php';

require_once 'src/core/renderer.php';

require_once 'src/actions/art.php';

describe('Art Action', function () {
    // Mock
    function album_dbo_factory(\PDO $pdo)
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
        $this->assignCalls = array();
        $this->mockPdo = $this->createMock(\PDO::class);
        $this->mockRenderer = $this->createMock(PortfolioRenderer::class);
        $this
            ->mockRenderer
            ->expects($this->any())
            ->method('assign')
            ->with()
            ->willReturnCallback(function ($key, $val) {
                $this->assignCalls[] = [$key, $val];
            });

        $this->action = new Art();
    });

    describe('albumNameFormatter', function () {
        it('should return concatenated album names separated by comma from input data', function () {
            $input = [
                ['name' => 'one'],
                ['name' => 'two']
            ];
            $result = Art::albumNameFormatter($input);
            expect($result)->toBe('one, two');
        });
    });

    function runAssignTest(object $context, array|string $params, string $expectedKey)
    {
        if (!is_array($params)) {
            $params = [$params];
        }

        $context->action->render($context->mockPdo, $context->mockRenderer, $params);
        return array_find($context->assignCalls, function ($value) use ($expectedKey) {
            return $value[0] === $expectedKey;
        });
    }

    describe('Shared page rendering', function () {
        $subAction = 'default';

        it('should set page id', function () use ($subAction) {
            $this->mockRenderer->expects($this->once())->method('setPageId')->with('art');
            $this->action->render($this->mockPdo, $this->mockRenderer, [$subAction]);
        });

        it('should set imageRoot variable', function () use ($subAction) {
            $expectedKey = 'imageRoot';
            $result = runAssignTest($this, $subAction, $expectedKey);
            expect([$expectedKey, '/media/art/'])->toBe($result);
        });

        it('should set thumbDir variable', function () use ($subAction) {
            $expectedKey = 'thumbDir';
            $result = runAssignTest($this, $subAction, $expectedKey);
            expect([$expectedKey, 'thumbnails/'])->toBe($result);
        });

        it('should set imageDir variable', function () use ($subAction) {
            $expectedKey = 'imageDir';
            $result = runAssignTest($this, $subAction, $expectedKey);
            expect([$expectedKey, 'images/'])->toBe($result);
        });
    });

    describe('Album page behaviour', function () {
        $subAction = 'album';

        it('should assign album data', function () use ($subAction) {
            $expectedKey = 'album';
            $result = runAssignTest($this, $subAction, $expectedKey);
            expect([$expectedKey, MOCK_ALBUM_1])->toBe($result);
        });

        it('should assign promoted image index', function () use ($subAction) {
            $expectedKey = 'promotedImageIndex';
            $result = runAssignTest($this, $subAction, $expectedKey)[1];

            // Set by RNG based on album name etc.
            // [TODO] write tests for checking this logic
            expect($result)->toBeInt();
        });

        it('should assign images', function () use ($subAction) {
            $expectedKey = 'images';
            $result = runAssignTest($this, $subAction, $expectedKey);

            expect([$expectedKey, [MOCK_IMAGE_HORIZ, MOCK_IMAGE_VERT]])->toBe($result);
        });

        it('should assign total image count', function () use ($subAction) {
            $expectedKey = 'totalImageCount';
            $result = runAssignTest($this, $subAction, $expectedKey);

            expect([$expectedKey, MOCK_IMAGE_COUNT])->toBe($result);
        });

        it('should assign pagination data', function () use ($subAction) {
            $expectedKey = 'pagination';
            $result = runAssignTest($this, $subAction, $expectedKey)[1];

            expect($result['page'])->toBe(1); // Expected default page
            expect($result['prefix'])->toBe("/album/" . MOCK_ALBUM_1['album_id']);
            expect($result['items_per_page'])->toBe(MOCK_PER_PAGE);
            expect($result['total_items'])->toBe(MOCK_IMAGE_COUNT);
        });

        it('should display page on template', function () use ($subAction) {
            $this
                ->mockRenderer
                ->expects($this->once())
                ->method('displayPage')
                ->with('album');

            $this->action->render($this->mockPdo, $this->mockRenderer, [$subAction]);
        });
    });

    describe('Image page behaviour', function () {
        $subAction = 'view';

        it('should display page without assigning image data if image id not provided', function () use ($subAction) {
            $expectedKey = 'image';
            $result = runAssignTest($this, $subAction, $expectedKey);

            expect($result)->toBe(null);
        });

        it('should assign image data if image id provided', function () use ($subAction) {
            $expectedKey = 'image';
            $result = runAssignTest($this, [$subAction, 569], $expectedKey);

            expect([$expectedKey, MOCK_IMAGE_HORIZ])->toBe($result);
        });

        it('should display page on template', function () use ($subAction) {
            $this
                ->mockRenderer
                ->expects($this->once())
                ->method('displayPage')
                ->with('image');

            $this->action->render($this->mockPdo, $this->mockRenderer, [$subAction]);
        });
    });

    describe('Album list behaviour', function () {
        $subAction = 'albums';

        it('should assign album data', function () use ($subAction) {
            $expectedKey = 'albums';
            $result = runAssignTest($this, [$subAction], $expectedKey);
            expect([$expectedKey, [MOCK_ALBUM_1, MOCK_ALBUM_2]])->toBe($result);
        });

        it('should display page on template', function () use ($subAction) {
            $this
                ->mockRenderer
                ->expects($this->once())
                ->method('displayPage')
                ->with('album-list');

            $this->action->render($this->mockPdo, $this->mockRenderer, [$subAction]);
        });
    });
});
