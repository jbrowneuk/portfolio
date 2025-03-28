<?php
namespace jbrowneuk;

function get_posts($pdo) {
    $statement = $pdo->query('SELECT * FROM posts ORDER BY post_date DESC LIMIT 5'); // [todo] make configurable

    $posts = [];
    while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
        $post = [];
        $cols = ['slug', 'title', 'content', 'tag', 'post_date', 'modification_date'];
        foreach ($cols as $column) {
            $post[$column] = $row[$column];
        }

        $posts[] = $post;
    }

    return $posts;
}