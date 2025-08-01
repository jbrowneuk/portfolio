<?php

namespace jbrowneuk;

/**
 * Provides all SQL statements used by the posts database object
 */
final class PostsSQL
{
    public const WHERE_TAG = 'tags LIKE :tag';
    public const WHERE_PUBLISHED = 'published = 1';

    public const ORDER_POSTS = ' ORDER BY timestamp DESC LIMIT :offset, :limit';

    public const SELECT_POST_COUNT = 'SELECT count(post_id) AS total FROM posts';
    public const SELECT_POST_COUNT_PUBLISHED = self::SELECT_POST_COUNT . ' WHERE ' . self::WHERE_PUBLISHED;

    public const SELECT_POST_COUNT_TAG = 'SELECT count(post_id) AS total FROM posts WHERE ' . self::WHERE_TAG;
    public const SELECT_POST_COUNT_TAG_PUBLISHED = self::SELECT_POST_COUNT_TAG . ' AND ' . self::WHERE_PUBLISHED;

    private const SELECT_POSTS_ROOT = 'SELECT * FROM posts';
    public const SELECT_POSTS = self::SELECT_POSTS_ROOT . self::ORDER_POSTS;
    public const SELECT_POSTS_PUBLISHED = self::SELECT_POSTS_ROOT . ' WHERE ' . self::WHERE_PUBLISHED . self::ORDER_POSTS;
    public const SELECT_POSTS_TAGGED = self::SELECT_POSTS_ROOT . ' WHERE ' . self::WHERE_PUBLISHED . self::ORDER_POSTS;
    public const SELECT_POSTS_TAGGED_PUBLISHED = self::SELECT_POSTS_ROOT . ' WHERE ' . self::WHERE_TAG . ' AND ' . self::WHERE_PUBLISHED . self::ORDER_POSTS;

    public const SELECT_SINGLE_POST = 'SELECT * FROM posts where post_id = :postId';
}

class PostsDBO implements IPostsDBO
{
    /** The default number of posts per page */
    public const DEFAULT_POSTS_PER_PAGE = 5;

    /** The number of posts per page for this Database Object instance */
    private int $postsPerPage = self::DEFAULT_POSTS_PER_PAGE;

    /** Whether draft posts should be shown */
    private bool $draftsEnabled = false;

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

    public function showDrafts(bool $shown): void
    {
        $this->draftsEnabled = $shown;
    }

    public function getPostCount(?string $tag = null): int
    {
        if ($tag != null && strlen($tag) > 0) {
            $sql = $this->draftsEnabled ? PostsSQL::SELECT_POST_COUNT_TAG : PostsSQL::SELECT_POST_COUNT_TAG_PUBLISHED;
            $statement = $this->pdo->prepare($sql);
            $statement->execute(['tag' => "%$tag%"]);
        } else {
            $sql = $this->draftsEnabled ? PostsSQL::SELECT_POST_COUNT : PostsSQL::SELECT_POST_COUNT_PUBLISHED;
            $statement = $this->pdo->query($sql);
        }

        $row = $statement->fetch(\PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function getPostPaginationData(?string $tag = null): array
    {
        return array(
            'items_per_page' => $this->postsPerPage,
            'total_items' => $this->getPostCount($tag)
        );
    }

    public function getPosts(int $page = 1, ?string $tag = null): array
    {
        $offset = ($page > 0 ? $page - 1 : 1) * $this->postsPerPage;
        $params = ['offset' => $offset, 'limit' => $this->postsPerPage];

        if ($tag !== null && strlen($tag) > 0) {
            $sql = $this->draftsEnabled ? PostsSQL::SELECT_POSTS_TAGGED : PostsSQL::SELECT_POSTS_TAGGED_PUBLISHED;
            $params['tag'] = "%$tag%";
        } else {
            $sql = $this->draftsEnabled ? PostsSQL::SELECT_POSTS : PostsSQL::SELECT_POSTS_PUBLISHED;
        }

        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        $posts = [];
        while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $posts[] = $this->generatePostData($row);
        }

        return $posts;
    }

    public function getPost(string $postId): ?array
    {
        $statement = $this->pdo->prepare(PostsSQL::SELECT_SINGLE_POST);
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
    private function generatePostData(array $row): array
    {
        $post = [];
        $cols = ['post_id', 'title', 'content', 'timestamp', 'modified_timestamp', 'tags', 'published'];
        foreach ($cols as $column) {
            $post[$column] = $row[$column];
        }

        return $post;
    }
}
