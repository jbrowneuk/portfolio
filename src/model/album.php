<?php

namespace jbrowneuk;

/**
 * An object encapsulating a gallery album
 */
class Album
{
    public readonly string $id;
    public readonly string $name;
    public readonly string $description;
    public ?int $imageCount;

    /**
     * Constructs album data from a database row
     *
     * @param array $row the database row
     */
    public function __construct(array $row)
    {
        $this->id = $row['album_id'];
        $this->name = $row['name'];
        $this->description = $row['description'];
    }

    public function setImageCount(int $count): void
    {
        $this->imageCount = $count;
    }
}
