<?php

namespace jbrowneuk;

require_once 'src/interfaces/ipostsdbo.php';
require_once 'src/core/action.php';
require_once 'src/core/renderer.php';

require_once 'src/actions/journal.php';

const MOCK_POST = [
    'post_id' => 'post-id',
    'title' => 'title',
    'content' => 'content',
    'timestamp' => 1234567890,
    'modified_timestamp' => null,
    'tags' => 'abc'
];

describe('Journal Action', function () {
    // Mocks 
    class MockPostsDBO implements IPostsDBO
    {
        public function __construct(public readonly \PDO $pdo) {}

        public function getPostCount($tag = null)
        {
            return 1;
        }

        public function getPostPaginationData(?string $tag = null)
        {
            return array(
                'items_per_page' => 5,
                'total_items' => 50
            );
        }

        public function getPosts($page = 1, $tag = null)
        {
            return [];
        }

        public function getPost($id)
        {
            if ($id === MOCK_POST['post_id']) {
                return MOCK_POST;
            }

            return false;
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

    describe('Shared page rendering', function () {
        it('should set page id', function () {
            $this->mockRenderer->expects($this->once())->method('setPageId')->with('journal');
            $this->action->render($this->mockPdo, $this->mockRenderer, []);
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

    describe('renderPostList', function () {
        it('should assign post data from database', function () {
            $expectedKey = 'posts';
            $this->action->render($this->mockPdo, $this->mockRenderer, []);

            $result = array_find($this->assignCalls, function ($value) use ($expectedKey) {
                return $value[0] === $expectedKey;
            });
            expect([$expectedKey, []])->toBe($result);
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

        it('should display page on template', function () {
            $this
                ->mockRenderer
                ->expects($this->atLeastOnce())
                ->method('displayPage')
                ->with('post-list');

            $this->action->render($this->mockPdo, $this->mockRenderer, []);
        });
    });

    describe('renderSinglePost', function () {
        it('should fetch post data if post ID is provided after post in params', function () {
            $params = ['post', MOCK_POST['post_id']];

            $this->action->render($this->mockPdo, $this->mockRenderer, $params);

            $result = array_find($this->assignCalls, fn ($val) => $val[0] === 'post');
            expect($result)->toBe(['post', MOCK_POST]);
        });

        it('should not fetch post data if post ID is not provided in params', function () {
            $params = ['post'];

            $this->action->render($this->mockPdo, $this->mockRenderer, $params);

            $result = array_find($this->assignCalls, fn ($val) => $val[0] === 'post');
            expect($result)->toBeNull();
        });

        it('should display page on template', function () {
            $params = ['post', MOCK_POST['post_id']];

            $this
                ->mockRenderer
                ->expects($this->atLeastOnce())
                ->method('displayPage')
                ->with('single-post');

            $this->action->render($this->mockPdo, $this->mockRenderer, $params);
        });
    });
});
