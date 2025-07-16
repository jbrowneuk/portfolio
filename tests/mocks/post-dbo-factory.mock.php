<?php

namespace jbrowneuk;

const MOCK_POST = [
    'post_id' => 'post-id',
    'title' => 'title',
    'content' => 'content',
    'timestamp' => 1234567890,
    'modified_timestamp' => null,
    'tags' => 'abc'
];

function posts_dbo_factory()
{
    $mock = \Mockery::mock(IPostsDBO::class);
    $mock->shouldReceive('getPostCount')->andReturn(1);
    $mock->shouldReceive('getPostPaginationData')->andReturn([
        'items_per_page' => 5,
        'total_items' => 50
    ]);
    $mock->shouldReceive('getPosts')->andReturn([MOCK_POST]);

    $mock->shouldReceive('getPost')->with(MOCK_POST['post_id'])->andReturn(MOCK_POST);
    $mock->shouldReceive('getPost')->andReturn(false);

    return $mock;
}
