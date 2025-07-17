<?php

namespace jbrowneuk;

require_once 'src/interfaces/iaction.php';
require_once 'src/interfaces/ipostsdbo.php';

require_once 'src/core/renderer.php';

require_once 'tests/mocks/post-dbo-factory.mock.php';

require_once 'src/actions/rss.php';

describe('Journal RSS feed action', function () {
    beforeEach(function () {
        $this->assignCalls = array();

        $this->mockPdo = $this->createMock(\PDO::class);

        $this->mockRenderer = $this->createMock(PortfolioRenderer::class);
        $this
            ->mockRenderer
            ->expects($this->atLeastOnce())
            ->method('assign')
            ->with()
            ->willReturnCallback(function ($key, $val) {
                $this->assignCalls[] = [$key, $val];
            });

        $this->action = new RSS();
    });

    it('should set page id', function () {
        $this->mockRenderer->expects($this->once())->method('setPageId')->with('rss');
        $this->action->render($this->mockPdo, $this->mockRenderer, []);
    });

    it('should assign posts', function () {
        $expectedKey = 'posts';
        $this->action->render($this->mockPdo, $this->mockRenderer, []);

        $result = array_find($this->assignCalls, fn($value) => $value[0] === $expectedKey);
        expect([$expectedKey, [MOCK_POST]])->toBe($result);
    });

    it('should set Content-Type header', function () {})->todo('use of header() should be refactored for easier testing');

    it('should display page on template', function () {
        $this
            ->mockRenderer
            ->expects($this->atLeastOnce())
            ->method('displayPage')
            ->with('rss');

        $this->action->render($this->mockPdo, $this->mockRenderer, []);
    });
});

describe('Estimated read time calculation', function () {
    $wordPerMinuteCount = 200;

    it('should return [less than a minute read] for a zero-length text', function () {
        $result = RSS::calculateReadTime('');
        expect($result)->toBe('less than a minute read');
    });

    it('should return [less than a minute read] for texts containing fewer than half words per minute count', function () use ($wordPerMinuteCount) {
        $words = array_fill(0, ($wordPerMinuteCount / 2) - 1, 'A');
        $content = join(' ', $words);
        $result = RSS::calculateReadTime($content);
        expect($result)->toBe('less than a minute read');
    });

    it('should return [1 minute read] for texts containing words per minute count', function () use ($wordPerMinuteCount) {
        $words = array_fill(0, $wordPerMinuteCount, 'A');
        $content = join(' ', $words);
        $result = RSS::calculateReadTime($content);
        expect($result)->toBe('1 minute read');
    });

    it('should calculate correct minute length for texts', function () use ($wordPerMinuteCount) {
        $multiplier = 4;
        $words = array_fill(0, $wordPerMinuteCount * $multiplier, 'A');
        $content = join(' ', $words);
        $result = RSS::calculateReadTime($content);
        expect($result)->toBe("$multiplier minute read");
    });
});
