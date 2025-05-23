<?php

namespace jbrowneuk;

require_once 'src/core/action.php';
require_once 'src/core/renderer.php';

require_once 'src/actions/journal.php';

describe('Journal Action', function () {
    // Mocks 
    class MockPostsDBO
    {
        public function __construct(public readonly \PDO $pdo) {}

        public function getPostPaginationData(?string $tag = null)
        {
            return array(
                'items_per_page' => 5,
                'total_items' => 50
            );
        }

        public function getPosts()
        {
            return [];
        }
    }

    function posts_dbo_factory(\PDO $pdo)
    {
        return new MockPostsDBO($pdo);
    }

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

        $this->action = new Journal();
    });

    it('should set page id', function () {
        $this->mockRenderer->expects($this->once())->method('setPageId')->with('journal');
        $this->action->render($this->mockPdo, $this->mockRenderer, []);
    });

    it('should assign post data from database', function () {
        $expectedKey = 'posts';
        $this->action->render($this->mockPdo, $this->mockRenderer, []);

        $result = array_find($this->assignCalls, function ($value) use ($expectedKey) {
            return $value[0] === $expectedKey;
        });
        expect([$expectedKey, []])->toBe($result);
    });

    it('should display page on template', function () {
        $this
            ->mockRenderer
            ->expects($this->atLeastOnce())
            ->method('displayPage')
            ->with('post-list');

        $this->action->render($this->mockPdo, $this->mockRenderer, []);
    });

    it('should have pagination data', function () {
        $expectedKey = 'pagination';
        $expectedPaginationData = array(
            'page' => 1,
            'items_per_page' => 5,
            'total_items' => 50
        );

        $this->action->render($this->mockPdo, $this->mockRenderer, []);

        $result = array_find($this->assignCalls, function ($value) use ($expectedKey) {
            return $value[0] === $expectedKey;
        });
        expect([$expectedKey, $expectedPaginationData])->toBe($result);
    });

    it('should assign stale timestamp', function () {
        $expectedKey = 'staleTimestamp';
        $expectedTimestamp = time() - (60 * 60 * 24 * 365 * 2);
        $this->action->render($this->mockPdo, $this->mockRenderer, []);

        $result = array_find($this->assignCalls, function ($value) use ($expectedKey) {
            return $value[0] === $expectedKey;
        });
        expect([$expectedKey, $expectedTimestamp])->toBe($result);
    });
});
