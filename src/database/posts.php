<?php

namespace jbrowneuk;

function get_posts($pdo)
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

{
    $itemsPerPage = 5;
    $statement = $pdo->query('SELECT * FROM posts ORDER BY timestamp DESC LIMIT ' . $itemsPerPage);

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
