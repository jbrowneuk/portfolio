<?php
namespace jbrowneuk;

require_once 'src/core/page.php';
require_once 'src/core/renderer.php';

require_once 'src/actions/art.php';

beforeEach(function () {
    $this->assignCalls = array();
    $this->mockPdo = $this->createMock(\PDO::class);
    $this->mockRenderer = $this->createMock(PortfolioRenderer::class);
    $this
        ->mockRenderer
        ->expects($this->atLeastOnce())
        ->method('assign')
        ->with()
        ->willReturnCallback(function ($key, $val) { $this->assignCalls[] = [$key, $val]; });

    $this->action = new Art();
});

it('should set page id', function () {
    $this->mockRenderer->expects($this->once())->method('setPageId')->with('art');
    $this->action->render($this->mockPdo, $this->mockRenderer, []);
});

function runAssignTest($context, $expectedKey)
{
    $context->action->render($context->mockPdo, $context->mockRenderer, []);
    return array_find($context->assignCalls, function ($value) use ($expectedKey) { return $value[0] === $expectedKey; });
}

it('should assign album name', function () {
    $expectedKey = 'albumName';
    $result = runAssignTest($this, $expectedKey);
    expect([$expectedKey, 'Featured'])->toBe($result);
});

it('should assign promoted image index', function () {
    $expectedKey = 'promotedImageIndex';
    $result = runAssignTest($this, $expectedKey);
    expect([$expectedKey, -1])->toBe($result);
});

it('should assign images', function () {
    $expectedKey = 'images';
    $result = runAssignTest($this, $expectedKey);
    expect([$expectedKey, []])->toBe($result);
});

it('should display page on template', function () {
    $this
        ->mockRenderer
        ->expects($this->once())
        ->method('displayPage')
        ->with('album');

    $this->action->render($this->mockPdo, $this->mockRenderer, []);
});