<?php

namespace jbrowneuk;

require_once 'src/interfaces/iauthentication.php';
require_once 'src/interfaces/irenderer.php';

require_once 'src/actions/editor.php';

describe('Editor Action', function () {
    beforeEach(function () {
        $this->postsDbo = \Mockery::spy(IPostsDBO::class);
        $this->mockAuth = \Mockery::mock(IAuthentication::class);

        $this->assignCalls = array();
        $this->mockRenderer = \Mockery::spy(IRenderer::class);
        $this->mockRenderer->shouldReceive('assign')->andReturnUsing(function ($key, $val) {
            $this->assignCalls[$key] = $val;
        });

        $this->action = new Editor($this->postsDbo, $this->mockAuth, $this->mockRenderer);
    });

    afterEach(function () {
        \Mockery::close();
    });

    describe('When authenticated', function () {
        beforeEach(function () {
            $this->mockAuth->shouldReceive('isAuthenticated')->andReturn(true);

            $this->postsDbo->shouldReceive('getPosts')->andReturn([]);
            $this->postsDbo->shouldReceive('getPostCount')->andReturn(1);
            $this->postsDbo->shouldReceive('getPostPaginationData')->andReturn([
                'items_per_page' => 5,
                'total_items' => 50
            ]);
        });

        it('should set page id', function () {
            $this->mockRenderer->shouldReceive('setPageId')->with('admin')->once();
            ($this->action)([]);
        });

        it('should display page on template', function () {
            $this
                ->mockRenderer
                ->shouldReceive('displayPage')
                ->with('editor')
                ->once();

            ($this->action)([]);
        });
    });

    describe('When not authenticated', function () {
        beforeEach(function () {
            $this->mockAuth->shouldReceive('isAuthenticated')->andReturn(false);
        });

        it('should redirect to auth action', function () {
            $this->mockRenderer->shouldReceive('redirectTo')->with('auth')->once();
            ($this->action)([]);
        });
    });

    describe('Post list sub-action', function () {
        beforeEach(function () {
            $this->mockAuth->shouldReceive('isAuthenticated')->andReturn(true);
        });

        it('should configure the posts database object', function () {
            $this->postsDbo->shouldReceive('getPosts')->andReturn([]);
            $this->postsDbo->shouldReceive('getPostCount')->andReturn(1);
            $this->postsDbo->shouldReceive('getPostPaginationData')->andReturn([]);

            ($this->action)([]);

            $this->postsDbo->shouldHaveReceived('setPostsPerPage')->once()->with(16);
            $this->postsDbo->shouldHaveReceived('showDrafts')->once()->with(true);
        });

        it('should assign correct properties', function () {
            $expectedPaginationData = ['items_per_page' => 5, 'total_items' => 50];
            $expectedPagination = ['page' => 1, ...$expectedPaginationData];
            $expectedPosts = [MOCK_POST];

            $this->postsDbo->shouldReceive('getPosts')->andReturn($expectedPosts);
            $this->postsDbo->shouldReceive('getPostCount')->andReturn(1);
            $this->postsDbo->shouldReceive('getPostPaginationData')->andReturn($expectedPaginationData);

            ($this->action)([]);

            expect($this->assignCalls['pagination'])->toEqual($expectedPagination);
            expect($this->assignCalls['posts'])->toEqual($expectedPosts);
        });
    });
});
