<?php

namespace jbrowneuk;

/**
 * An object encapsulating a journal post
 */
class Post
{
    public readonly string $id;
    public readonly string $title;
    public readonly string $content;
    public readonly int $timestamp;
    public readonly ?int $modified;
    public readonly array $tags;

    /**
     * Constructs post data from a database row
     *
     * @param array $row the database row
     */
    public function __construct(array $row)
    {
        $this->id = $row['post_id'];
        $this->title = $row['title'];
        $this->content = $row['content'];
        $this->timestamp = $row['timestamp'];
        $this->modified = $row['modified_timestamp'];
        $this->tags = explode(' ', $row['tags']);
    }
}
