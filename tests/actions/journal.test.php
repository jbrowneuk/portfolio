<?php

namespace jbrowneuk;

require_once 'src/interfaces/iaction.php';
require_once 'src/interfaces/ipostsdbo.php';

require_once 'src/core/renderer.php';

require_once 'tests/mocks/post-dbo-factory.mock.php';

require_once 'src/actions/journal.php';

describe('Journal Action', function () {
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

            $result = array_find($this->assignCalls, fn($value) => $value[0] === $expectedKey);
            expect([$expectedKey, $expectedTimestamp])->toBe($result);
        });
    });

    describe('renderPostList', function () {
        it('should assign post data from database', function () {
            $expectedKey = 'posts';
            $this->action->render($this->mockPdo, $this->mockRenderer, []);

            $result = array_find($this->assignCalls, fn($value) => $value[0] === $expectedKey);
            expect([$expectedKey, [MOCK_POST]])->toBe($result);
        });

        it('should have pagination data', function () {
            $expectedKey = 'pagination';
            $expectedPaginationData = array(
                'page' => 1,
                'items_per_page' => 5,
                'total_items' => 50
            );

            $this->action->render($this->mockPdo, $this->mockRenderer, []);

            $result = array_find($this->assignCalls, fn($value) => $value[0] === $expectedKey);
            expect([$expectedKey, $expectedPaginationData])->toBe($result);
        });

        // Tag is not stored in the class anywhere, but is assigned to the template
        it('should assign tag if one exists in the page params', function () {
            $expectedTag = 'potato';
            $tagKey = 'tag';
            $paginationKey = 'pagination';

            $this->action->render($this->mockPdo, $this->mockRenderer, ['journal', 'tag', $expectedTag]);

            // Check tag assigned to template
            $tagResult = array_find($this->assignCalls, fn($value) => $value[0] === $tagKey);
            expect($tagResult)->toBe([$tagKey, $expectedTag]);

            // Check pagination has the tag prefix
            $paginationResult = array_find($this->assignCalls, fn($value) => $value[0] === $paginationKey);
            expect($paginationResult[1]['prefix'])->toBe("/tag/$expectedTag");
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

            $result = array_find($this->assignCalls, fn($val) => $val[0] === 'post');
            expect($result)->toBe(['post', MOCK_POST]);
        });

        it('should not fetch post data if post ID is not provided in params', function () {
            $params = ['post'];

            $this->action->render($this->mockPdo, $this->mockRenderer, $params);

            $result = array_find($this->assignCalls, fn($val) => $val[0] === 'post');
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
