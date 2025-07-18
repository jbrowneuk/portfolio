<?php

namespace jbrowneuk;

const EXPECTED_POST_COUNT = 5;

require_once 'src/database/posts.dbo.php';

describe('Posts Database Object', function () {
    beforeEach(function () {
        $this->mockStatement = \Mockery::mock(\PDOStatement::class);
        $this->mockPdo = \Mockery::mock(\PDO::class);
    });

    describe('getPostCount', function () {
        beforeEach(function () {
            $this->mockStatement
                ->shouldReceive('fetch')
                ->andReturn(['total' => EXPECTED_POST_COUNT]);

            $this->postsDbo = new PostsDBO($this->mockPdo);
        });

        it('should fetch post count of all posts if tag not provided', function () {
            $this->mockPdo
                ->shouldReceive('query')
                ->with('SELECT count(post_id) AS total FROM posts')
                ->andReturn($this->mockStatement)
                ->once();

            expect($this->postsDbo->getPostCount())->toBe(EXPECTED_POST_COUNT);
        });

        it('should fetch post count of tagged posts if tag is provided', function () {
            $tag = 'anything';

            $this->mockStatement
                ->shouldReceive('execute')
                ->with(['tag' => "%$tag%"])
                ->once();

            $this->mockPdo
                ->shouldReceive('prepare')
                ->with('SELECT count(post_id) AS total FROM posts WHERE tags LIKE :tag')
                ->andReturn($this->mockStatement)
                ->once();

            expect($this->postsDbo->getPostCount($tag))->toBe(EXPECTED_POST_COUNT);
        });
    });

    describe('getPostPaginationData', function () {
        beforeEach(function () {
            $this->postsDbo = \Mockery::mock(PostsDBO::class)->makePartial();
        });

        it('should return items per page', function () {
            $this->postsDbo->shouldReceive('getPostCount')->andReturn(0);

            $pagination = $this->postsDbo->getPostPaginationData();

            expect($pagination['items_per_page'])->toBe(PostsDBO::POSTS_PER_PAGE);
        });

        it('should return total items if tag not specified', function () {
            $expectedCount = 16;

            $this->postsDbo->shouldReceive('getPostCount')->andReturn($expectedCount);

            $pagination = $this->postsDbo->getPostPaginationData();

            expect($pagination['total_items'])->toBe($expectedCount);
        });

        it('should pass tag to post count', function () {
            $tag = 'any';

            $this->postsDbo->shouldReceive('getPostCount')->with($tag)->andReturn(0);

            $pagination = $this->postsDbo->getPostPaginationData($tag);

            expect($pagination)->toBeTruthy();
        });
    });

    describe('getPosts', function () {
        beforeEach(function () {
            $this->mockStatement
                ->shouldReceive('fetch')
                ->andReturn([]);

            $this->postsDbo = new PostsDBO($this->mockPdo);
        });

        it('should fetch a page of posts if tag not provided', function () {
            $this->mockStatement
                ->shouldReceive('execute')
                ->with(['offset' => 0, 'limit' => PostsDBO::POSTS_PER_PAGE])
                ->once();

            $this->mockPdo
                ->shouldReceive('prepare')
                ->with('SELECT * FROM posts ORDER BY timestamp DESC LIMIT :offset, :limit')
                ->andReturn($this->mockStatement)
                ->once();

            expect($this->postsDbo->getPosts(1))->toBe([]);
        });

        it('should fetch a page of posts filtered to tag if tag provided', function () {
            $tag = 'blog-post';

            $this->mockStatement
                ->shouldReceive('execute')
                ->with(['offset' => 0, 'limit' => PostsDBO::POSTS_PER_PAGE, 'tag' => "%$tag%"])
                ->once();

            $this->mockPdo
                ->shouldReceive('prepare')
                ->with('SELECT * FROM posts WHERE tags LIKE :tag ORDER BY timestamp DESC LIMIT :offset, :limit')
                ->andReturn($this->mockStatement)
                ->once();

            expect($this->postsDbo->getPosts(1, $tag))->toBe([]);
        });

        it('should calculate correct page offset', function () {
            $page = 5;
            $expectedOffset = ($page - 1) * PostsDBO::POSTS_PER_PAGE; // Zero-based pagination

            $this->mockStatement
                ->shouldReceive('execute')
                ->with(['offset' => $expectedOffset, 'limit' => PostsDBO::POSTS_PER_PAGE])
                ->once();

            $this->mockPdo
                ->shouldReceive('prepare')
                ->with('SELECT * FROM posts ORDER BY timestamp DESC LIMIT :offset, :limit')
                ->andReturn($this->mockStatement)
                ->once();

            expect($this->postsDbo->getPosts($page))->toBe([]);
        });
    });

    describe('getPost', function () {
        beforeEach(function () {
            $this->postsDbo = new PostsDBO($this->mockPdo);

            $this->mockStatement
                ->shouldReceive('fetch')
                ->andReturn(MOCK_POST);
        });

        it('should prepare using correct SQL', function () {
            $this->mockStatement
                ->shouldReceive('execute')
                ->once();

            $this->mockPdo
                ->shouldReceive('prepare')
                ->with('SELECT * FROM posts where post_id = :postId')
                ->andReturn($this->mockStatement)
                ->once();

            expect($this->postsDbo->getPost('anything'))->toBe(MOCK_POST);
        });

        it('should fetch correct post using ID', function () {
            $expectedId = 'hello-world';

            $this->mockStatement
                ->shouldReceive('execute')
                ->with(['postId' => $expectedId])
                ->once();

            $this->mockPdo
                ->shouldReceive('prepare')
                ->andReturn($this->mockStatement)
                ->once();

            expect($this->postsDbo->getPost($expectedId))->toBe(MOCK_POST);
        });
    });
});
