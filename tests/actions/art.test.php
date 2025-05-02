<?php

namespace jbrowneuk;

// Values set in mocks
$mockAlbumImageCount = null;
$mockAlbum = null;
$mockImageHorizontal = null;
$mockImageVertical = null;

// Mocks
function get_album($pdo, $albumId)
{
    global $mockAlbum;
    if (!isset($mockAlbum)) {
        $mockAlbum = ['album_id' => 'album_id', 'name' => 'album', 'description' => 'album desc'];
    }

    return $mockAlbum;
}

function get_image_count_for_album($pdo, $albumId)
{
    global $mockAlbumImageCount;
    if (!isset($mockAlbumImageCount)) {
        $mockAlbumImageCount = 1024;
    }

    return $mockAlbumImageCount;
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

require_once 'src/core/page.php';
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

function runAssignTest($context, $action, $expectedKey)
{
    $context->action->render($context->mockPdo, $context->mockRenderer, [$action]);
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

    it('should display page on template', function () use ($subAction) {
        $this
            ->mockRenderer
            ->expects($this->once())
            ->method('displayPage')
            ->with('album');

        $this->action->render($this->mockPdo, $this->mockRenderer, [$subAction]);
    });
});
