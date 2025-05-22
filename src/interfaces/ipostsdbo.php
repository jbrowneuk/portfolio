<?php

namespace jbrowneuk;

/**
 * An interface encapsulating the Database Object pertaining to post data
 */
interface IPostsDBO
{
    /**
     * Gets the total post count, optionally scoped to a specific tag
     *
     * @param ?string $tag (optional) tag to scope the count to
     *
     * @return int total count of posts in the search scope
     */
    public function getPostCount(?string $tag = null);

    /**
     * Gets the data required for pagination, optionally scoped to a specific
     * tag. Returns the expected number of posts for a page and the total
     * number of posts.
     *
     * @param ?string $tag (optional) tag to scope the count to
     *
     * @return array pagination data (for the specified tag if provided)
     */
    public function getPostPaginationData(?string $tag = null);

    /**
     * Gets a page of post data, optionally scoped to a specific tag
     *
     * @param int $page the page to get data from, defaults to 1
     * @param ?string $tag (optional) tag to scope the count to
     *
     * @return array array of post data
     */
    public function getPosts(int $page = 1, ?string $tag = null);

    /**
     * Gets a specific post's data
     *
     * @param string $postId the ID of the post to fetch
     *
     * @return array post data
     */
    public function getPost(string $postId);
}
