<?php

namespace jbrowneuk;

require_once 'src/interfaces/ipostsdbo.php';
require_once 'src/interfaces/irenderer.php';

require_once 'src/actions/rss.php';

describe('Journal RSS feed action', function () {
    beforeEach(function () {
        $this->postsDBO = \Mockery::mock(IPostsDBO::class);
        $this->postsDBO->shouldReceive('getPosts')->andReturn([MOCK_POST]);

        $this->assignCalls = array();
        $this->mockRenderer = \Mockery::spy(IRenderer::class);
        $this->mockRenderer->shouldReceive('assign')->andReturnUsing(function ($key, $val) {
            $this->assignCalls[$key] = $val;
        });

        $this->action = new RSS($this->postsDBO, $this->mockRenderer);
        ($this->action)([]);
    });

    afterEach(function () {
        \Mockery::close();
    });

    it('should set page id', function () {
        $this->mockRenderer->shouldHaveReceived('setPageId')->with('rss')->once();
    });

    it('should assign posts', function () {
        expect($this->assignCalls['posts'])->toBe([MOCK_POST]);
    });

    it('should set Content-Type header', function () {})->todo('use of header() should be refactored for easier testing');

    it('should display page on template', function () {
        $this->mockRenderer->shouldHaveReceived('displayPage')->with('rss')->once();
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
