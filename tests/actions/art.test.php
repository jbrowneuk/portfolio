<?php

namespace jbrowneuk;

// Values set in mocks. [TODO] figure out how to make these work here instead of
// having to define them in this way. Placing them here makes null get returned
// from the mock functions below.
$mockAlbumImageCount = null;
$mockAlbum = null;
$mockAlbum2 = null;
$mockImageHorizontal = null;
$mockImageVertical = null;
$mockImage = null;

// Mocks
function get_album($pdo, $albumId)
{
    global $mockAlbum;
    if (!isset($mockAlbum)) {
        $mockAlbum = [
            'album_id' => 'album_id',
            'name' => 'album',
            'description' => 'album desc'
        ];
    }

    return $mockAlbum;
}

function get_albums($pdo)
{
    global $mockAlbum, $mockAlbum2;
    if (!isset($mockAlbum) || !isset($mockAlbum2)) {
        $mockAlbum = [
            'album_id' => 'album_id_1',
            'name' => 'album',
            'description' => 'album desc'
        ];

        $mockAlbum2 = [
            'album_id' => 'album_id_2',
            'name' => 'album',
            'description' => 'album desc'
        ];
    }

    return [$mockAlbum, $mockAlbum2];
}

function get_image_count_for_album($pdo, $albumId)
{
    global $mockAlbumImageCount;
    if (!isset($mockAlbumImageCount)) {
        $mockAlbumImageCount = 1024;
    }

    return $mockAlbumImageCount;
}

function get_album_pagination_data($pdo, $albumId)
{
    return array(
        'items_per_page' => 5,
        'total_items' => get_image_count_for_album($pdo, $albumId)
    );
}

function get_images_for_album($pdo, $albumId, $page)
{
    global $mockImageHorizontal, $mockImageVertical;
    if (!isset($mockImageHorizontal)) {
        $mockImageHorizontal = [
            'image_id' => 1,
            'title' => 'i',
            'filename' => '1.jpg',
            'description' => 'd1',
            'timestamp' => 0,
            'width' => 100,
            'height' => 50
        ];
    }

    if (!isset($mockImageVertical)) {
        $mockImageVertical = [
            'image_id' => 2,
            'title' => '2',
            'filename' => '2.jpg',
            'description' => 'd2',
            'timestamp' => 0,
            'width' => 50,
            'height' => 100
        ];
    }

    return [$mockImageHorizontal, $mockImageVertical];
}

function get_image($pdo, $imageId)
{
    global $mockImage;

    if (!isset($mockImage)) {
        $mockImage = [
            'image_id' => 1,
            'title' => 'image',
            'filename' => 'image.jpg',
            'description' => 'mock',
            'timestamp' => 0,
            'width' => 1,
            'height' => 1
        ];
    }

    return $mockImage;
}

require_once 'src/core/action.php';
require_once 'src/core/renderer.php';

require_once 'src/actions/art.php';

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

describe('modifier_album_names', function () {
    it('should return input if not an array', function () {
        $input = 1024;
        $result = modifier_album_names($input);
        expect($result)->toBe($input);
    });

    it('should return concatenated album names separated by comma from input data', function () {
        $input = [
            ['name' => 'one'],
            ['name' => 'two']
        ];
        $result = modifier_album_names($input);
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
        global $mockAlbum;
        $expectedKey = 'album';
        $result = runAssignTest($this, $subAction, $expectedKey);
        expect([$expectedKey, $mockAlbum])->toBe($result);
    });

    it('should assign promoted image index', function () use ($subAction) {
        $expectedKey = 'promotedImageIndex';
        $expectedValue = 1; // Set by RNG based on album name etc, known value provided
        $result = runAssignTest($this, $subAction, $expectedKey);
        expect([$expectedKey, $expectedValue])->toBe($result);
    });

    it('should assign images', function () use ($subAction) {
        global $mockImageHorizontal, $mockImageVertical;

        $expectedKey = 'images';
        $result = runAssignTest($this, $subAction, $expectedKey);
        expect([$expectedKey, [$mockImageHorizontal, $mockImageVertical]])->toBe($result);
    });

    it('should assign total image count', function () use ($subAction) {
        global $mockAlbumImageCount;
        $expectedKey = 'totalImageCount';
        $result = runAssignTest($this, $subAction, $expectedKey);
        expect([$expectedKey, $mockAlbumImageCount])->toBe($result);
    });

    it('should assign pagination data', function () use ($subAction) {
        global $mockAlbum;
        $expectedKey = 'pagination';
        $result = runAssignTest($this, $subAction, $expectedKey)[1];

        // Expectation: this calls the mock
        $mockPaginationData = get_album_pagination_data(null, null);

        expect($result['page'])->toBe(1); // Expected default page
        expect($result['prefix'])->toBe("/album/{$mockAlbum['album_id']}");
        expect($result['items_per_page'])->toBe($mockPaginationData['items_per_page']);
        expect($result['total_items'])->toBe($mockPaginationData['total_items']);
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
        global $mockImage;
        $expectedKey = 'image';
        $result = runAssignTest($this, [$subAction, 569], $expectedKey);
        expect([$expectedKey, $mockImage])->toBe($result);
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
        global $mockAlbum, $mockAlbum2;
        $expectedKey = 'albums';
        $result = runAssignTest($this, [$subAction], $expectedKey);
        expect([$expectedKey, [$mockAlbum, $mockAlbum2]])->toBe($result);
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
