<?php

namespace jbrowneuk;

require_once 'src/model/post.php';

const MOCK_POST_ROW = [
    'post_id' => 'post-id',
    'title' => 'title',
    'content' => 'content',
    'timestamp' => 1234567890,
    'modified_timestamp' => null,
    'tags' => 'abc'
];

const MOCK_POST = new Post(MOCK_POST_ROW);
