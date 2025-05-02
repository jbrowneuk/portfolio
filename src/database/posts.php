<?php

namespace jbrowneuk;

function get_posts($pdo)
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
