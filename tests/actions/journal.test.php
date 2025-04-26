<?php
namespace jbrowneuk;

// Mocks
function get_posts($pdo) {
    return [];
}

require_once 'src/core/page.php';
require_once 'src/core/renderer.php';

require_once 'src/actions/journal.php';

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

    $this->action = new Journal();
});

it('should set page id', function () {
    $this->mockRenderer->expects($this->once())->method('setPageId')->with('journal');
    $this->action->render($this->mockPdo, $this->mockRenderer);
});

it('should assign post data from database', function () {
    $expectedKey = 'posts';
    $this->action->render($this->mockPdo, $this->mockRenderer);

    $result = array_find($this->assignCalls, function ($value) use ($expectedKey) { return $value[0] === $expectedKey; });
    expect([$expectedKey, []])->toBe($result);
});

it('should display page on template', function () {
    $this
        ->mockRenderer
        ->expects($this->atLeastOnce())
        ->method('displayPage')
        ->with('post-list');

    $this->action->render($this->mockPdo, $this->mockRenderer);
});