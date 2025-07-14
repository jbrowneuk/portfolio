<?php

namespace jbrowneuk;

require_once 'src/interfaces/ipostsdbo.php';
require_once 'src/core/action.php';
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
