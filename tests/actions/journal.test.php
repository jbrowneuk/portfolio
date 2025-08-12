<?php

namespace jbrowneuk;

require_once 'src/interfaces/ipostsdbo.php';
require_once 'src/interfaces/irenderer.php';

require_once 'src/actions/journal.php';

describe('Journal Action', function () {
    function mockPostDbo() {
        $postsDBO = \Mockery::mock(IPostsDBO::class);
        $postsDBO->shouldReceive('getPostCount')->andReturn(1);
        $postsDBO->shouldReceive('getPostPaginationData')->andReturn([
            'items_per_page' => 5,
            'total_items' => 50
        ]);
        $postsDBO->shouldReceive('getPosts')->andReturn([MOCK_POST]);

        $postsDBO->shouldReceive('getPost')->with(MOCK_POST['post_id'])->andReturn(MOCK_POST);
        $postsDBO->shouldReceive('getPost')->andReturn(false);

        return $postsDBO;
    }

    beforeEach(function () {
        $this->postsDBO = mockPostDbo();

        $this->assignCalls = array();
        $this->mockRenderer = \Mockery::spy(IRenderer::class);
        $this->mockRenderer->shouldReceive('assign')->andReturnUsing(function ($key, $val) {
            $this->assignCalls[$key] = $val;
        });

        $this->action = new Journal($this->postsDBO, $this->mockRenderer);
    });

    afterEach(function () {
        \Mockery::close();
    });

    describe('Shared page rendering', function () {
        beforeEach(function () {
            ($this->action)([]);
        });

        it('should set page id', function () {
            $this->mockRenderer->shouldHaveReceived('setPageId')->with('journal')->once();
        });

        it('should assign stale timestamp', function () {
            $expectedTimestamp = time() - (60 * 60 * 24 * 365 * 2);
            expect($this->assignCalls['staleTimestamp'])->toBe($expectedTimestamp);
        });
    });

    describe('renderPostList', function () {
        it('should assign post data from database', function () {
            ($this->action)([]);

            expect($this->assignCalls['posts'])->toBe([MOCK_POST]);
        });

        it('should have pagination data', function () {
            $expectedPaginationData = array(
                'page' => 1,
                'items_per_page' => 5,
                'total_items' => 50
            );

            ($this->action)([]);

            expect($this->assignCalls['pagination'])->toBe($expectedPaginationData);
        });

        // Tag is not stored in the class anywhere, but is assigned to the template
        it('should assign tag if one exists in the page params', function () {
            $expectedTag = 'potato';

            ($this->action)(['journal', 'tag', $expectedTag]);

            // Check tag assigned to template
            expect($this->assignCalls['tag'])->toBe($expectedTag);

            // Check pagination has the tag prefix
            expect($this->assignCalls['pagination']['prefix'])->toBe("/tag/$expectedTag");
        });

        it('should display page on template', function () {
            ($this->action)([]);
            $this->mockRenderer->shouldHaveReceived('displayPage')->with('post-list')->once();
        });
    });

    describe('renderSinglePost', function () {
        it('should fetch post data if post ID is provided after post in params', function () {
            ($this->action)(['post', MOCK_POST['post_id']]);
            expect($this->assignCalls['post'])->toBe(MOCK_POST);
        });

        it('should not fetch post data if post ID is not provided in params', function () {
            ($this->action)(['post']);
            expect(array_key_exists('post', $this->assignCalls))->toBeFalse();
        });

        it('should display page on template', function () {
            ($this->action)(['post', MOCK_POST['post_id']]);
            $this->mockRenderer->shouldHaveReceived('displayPage')->with('single-post')->once();
        });
    });
});
