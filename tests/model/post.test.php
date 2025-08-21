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
        expect($post->published)->toBe(MOCK_POST_ROW['published'] === 1);
    });

    it('should parse tags into array correctly', function () {
        $tags = 'one two three';
        $postWithTagsRow = [...MOCK_POST_ROW, 'tags' => $tags];

        $post = new Post($postWithTagsRow);

        expect($post->tags)->toBe(explode(' ', $tags));
    });

    it('should parse published state correctly', function () {
        $postPublishedRow = [...MOCK_POST_ROW, 'published' => 1];
        $postDraftRow = [...MOCK_POST_ROW, 'published' => 0];

        $postPublished = new Post($postPublishedRow);
        expect($postPublished->published)->toBeTrue();

        $postDraft = new Post($postDraftRow);
        expect($postDraft->published)->toBeFalse();
    });
});
