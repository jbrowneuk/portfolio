<?php

namespace jbrowneuk;

class PostsDBO implements IPostsDBO
{
    const POSTS_PER_PAGE = 5;

    /**
     * Constructs an instance of the Posts Database Object
     *
     * @param \PDO $pdo a connected PDO object
     */
    public function __construct(private readonly \PDO $pdo) {}

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

    public function getPostPaginationData(?string $tag = null)
    {
        return array(
            'items_per_page' => self::POSTS_PER_PAGE,
            'total_items' => $this->getPostCount($tag)
        );
    }

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
            $posts[] = $this->generatePostData($row);
        }

        return $posts;
    }

    /**
     * Constructs post data from a database row
     *
     * @param array $row the database row
     *
     * @return array post data array
     */
    private function generatePostData(array $row)
    {
        $post = [];
        $cols = ['post_id', 'title', 'content', 'timestamp', 'modified_timestamp', 'tags'];
        foreach ($cols as $column) {
            $post[$column] = $row[$column];
        }

        return $post;
    }
}
