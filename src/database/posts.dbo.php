<?php

namespace jbrowneuk;

/**
 * Provides all SQL statements used by the posts database object
 */
final class PostsSQL
{
    private const WHERE_TAG = 'tags LIKE :tag';

    public const ORDER_POSTS = ' ORDER BY timestamp DESC LIMIT :offset, :limit';

    public const SELECT_POST_COUNT = 'SELECT count(post_id) AS total FROM posts';
    public const SELECT_POST_COUNT_TAGGED = 'SELECT count(post_id) AS total FROM posts WHERE ' . self::WHERE_TAG;

    private const SELECT_POSTS_ROOT = 'SELECT * FROM posts';
    public const SELECT_POSTS = self::SELECT_POSTS_ROOT . self::ORDER_POSTS;
    public const SELECT_POSTS_TAGGED = self::SELECT_POSTS_ROOT . ' WHERE ' . self::WHERE_TAG . self::ORDER_POSTS;

    public const SELECT_SINGLE_POST = 'SELECT * FROM posts where post_id = :postId';
}

class PostsDBO implements IPostsDBO
{
    /** The default number of posts per page */
    public const DEFAULT_POSTS_PER_PAGE = 5;

    /** The number of posts per page for this Database Object instance */
    private int $postsPerPage = self::DEFAULT_POSTS_PER_PAGE;

    /**
     * Constructs an instance of the Posts Database Object
     *
     * @param \PDO $pdo a connected PDO object
     */
    public function __construct(private readonly \PDO $pdo) {}

    public function setPostsPerPage(int $posts): void
    {
        $this->postsPerPage = $posts;
    }

    public function getPostCount(?string $tag = null)
    {
        if ($tag != null && strlen($tag) > 0) {
            $statement = $this->pdo->prepare(PostsSQL::SELECT_POST_COUNT_TAGGED);
            $statement->execute(['tag' => "%$tag%"]);
        } else {
            $statement = $this->pdo->query(PostsSQL::SELECT_POST_COUNT);
        }

        $row = $statement->fetch(\PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function getPostPaginationData(?string $tag = null)
    {
        return array(
            'items_per_page' => $this->postsPerPage,
            'total_items' => $this->getPostCount($tag)
        );
    }

    public function getPosts(int $page = 1, ?string $tag = null)
    {
        $offset = ($page > 0 ? $page - 1 : 1) * $this->postsPerPage;
        $params = ['offset' => $offset, 'limit' => $this->postsPerPage];

        if ($tag !== null && strlen($tag) > 0) {
            $sql = PostsSQL::SELECT_POSTS_TAGGED;
            $params['tag'] = "%$tag%";
        } else {
            $sql = PostsSQL::SELECT_POSTS;
        }

        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        $posts = [];
        while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $posts[] = $this->generatePostData($row);
        }

        return $posts;
    }

    public function getPost(string $postId)
    {
        $sql = PostsSQL::SELECT_SINGLE_POST;
        $statement = $this->pdo->prepare($sql);
        $statement->execute(['postId' => $postId]);

        $row = $statement->fetch(\PDO::FETCH_ASSOC);
        if ($row === false) {
            return null;
        }

        return $this->generatePostData($row);
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
