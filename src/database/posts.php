<?php

namespace jbrowneuk;

class PostsDBO
{
    const POSTS_PER_PAGE = 5;

    /**
     * Constructs an instance of the Posts Database Object
     *
     * @param \PDO $pdo a connected PDO object
     */
    public function __construct(private readonly \PDO $pdo) {}

    /**
     * Gets the total post count, optionally scoped to a specific tag
     *
     * @param ?string $tag (optional) tag to scope the count to
     *
     * @return int total count of posts in the search scope
     */
    public function getPostCount(?string $tag = null)
    {
        if ($tag != null && strlen($tag) > 0) {
            $statement = $this->pdo->prepare('SELECT count(post_id) AS total FROM posts WHERE tags LIKE :tag');
            $statement->execute(['tag' => "%$tag%"]);
        } else {
            $statement = $this->pdo->query('SELECT count(post_id) AS total FROM posts');
        }

        $row = $statement->fetch(\PDO::FETCH_ASSOC);
        return $row['total'];
    }

    /**
     * Gets the data required for pagination, optionally scoped to a specific
     * tag. Returns the expected number of posts for a page and the total
     * number of posts.
     *
     * @param ?string $tag (optional) tag to scope the count to
     *
     * @return array pagination data (for the specified tag if provided)
     */
    public function getPostPaginationData(?string $tag = null)
    {
        return array(
            'items_per_page' => self::POSTS_PER_PAGE,
            'total_items' => $this->getPostCount($tag)
        );
    }

    /**
     * Gets a page of post data, optionally scoped to a specific tag
     *
     * @param int $page the page to get data from, defaults to 1
     * @param ?string $tag (optional) tag to scope the count to
     *
     * @return array array of post data
     */
    public function getPosts(int $page = 1, ?string $tag = null)
    {
        $offset = ($page > 0 ? $page - 1 : 1) * self::POSTS_PER_PAGE;
        $params = ['offset' => $offset, 'limit' => self::POSTS_PER_PAGE];

        if ($tag !== null && strlen($tag) > 0) {
            $sql = 'SELECT * FROM posts WHERE tags LIKE :tag ORDER BY timestamp DESC LIMIT :offset, :limit';
            $params['tag'] = "%$tag%";
        } else {
            $sql = 'SELECT * FROM posts ORDER BY timestamp DESC LIMIT :offset, :limit';
        }

        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        $posts = [];
        while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $post = [];
            $cols = ['post_id', 'title', 'content', 'timestamp', 'modified_timestamp', 'tags'];
            foreach ($cols as $column) {
                $post[$column] = $row[$column];
            }

            $posts[] = $post;
        }

        return $posts;
    }
}
