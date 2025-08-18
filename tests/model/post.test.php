<?php

namespace jbrowneuk;

describe('Post object', function () {
    it('should set properties correctly', function () {
        $post = new Post(MOCK_POST_ROW);
        expect($post->id)->toBe(MOCK_POST_ROW['post_id']);
        expect($post->title)->toBe(MOCK_POST_ROW['title']);
        expect($post->content)->toBe(MOCK_POST_ROW['content']);
        expect($post->timestamp)->toBe(MOCK_POST_ROW['timestamp']);
        expect($post->modified)->toBe(MOCK_POST_ROW['modified_timestamp']);
        expect($post->tags)->toBe(explode(' ', MOCK_POST_ROW['tags']));
    });
});
