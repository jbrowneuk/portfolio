<?php

namespace jbrowneuk;

const POSTS_PER_PAGE = 5;

/**
 * Gets the total post count, optionally scoped to a specific tag
 *
 * @param \PDO $pdo a connected PDO object
 * @param ?string $tag (optional) tag to scope the count to
 *
 * @return int total count of posts in the search scope
 */
function get_post_count(\PDO $pdo, ?string $tag = null)
{
    if ($tag != null && strlen($tag) > 0) {
        $statement = $pdo->prepare('SELECT count(post_id) AS total FROM posts WHERE tags LIKE :tag');
        $statement->execute(['tag' => "%$tag%"]);
    } else {
        $statement = $pdo->query('SELECT count(post_id) AS total FROM posts');
    }

    $row = $statement->fetch(\PDO::FETCH_ASSOC);
    return $row['total'];
}

/**
 * Gets a page of post data, optionally scoped to a specific tag
 *
 * @param \PDO $pdo a connected PDO object
 * @param int $page the page to get data from, defaults to 1
 * @param ?string $tag (optional) tag to scope the count to
 *
 * @return array array of post data
 */
function get_posts(\PDO $pdo, int $page = 1, ?string $tag = null)
{
    $offset = ($page > 0 ? $page - 1 : 1) * POSTS_PER_PAGE;
    $params = ['offset' => $offset, 'limit' => POSTS_PER_PAGE];

    if ($tag !== null && strlen($tag) > 0) {
        $sql = 'SELECT * FROM posts WHERE tags LIKE :tag ORDER BY timestamp DESC LIMIT :offset, :limit';
        $params['tag'] = "%$tag%";
    } else {
        $sql = 'SELECT * FROM posts ORDER BY timestamp DESC LIMIT :offset, :limit';
    }

    $statement = $pdo->prepare($sql);
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
