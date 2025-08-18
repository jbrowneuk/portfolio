<?php

namespace jbrowneuk;

/**
 * An object encapsulating a gallery image
 */
class Image
{
    public readonly int $id;
    public readonly string $title;
    public readonly string $filename;
    public readonly string $description;
    public readonly int $timestamp;
    public readonly int $width;
    public readonly int $height;

    public array $albums;
    public bool $featured = false;

    /**
     * Constructs album image data from a database row
     *
     * @param array $row the database row
     */
    public function __construct(array $row)
    {
        $this->id = $row['image_id'];
        $this->title = $row['title'];
        $this->filename = $row['filename'];
        $this->description = isset($row['description']) ? $row['description'] : '';
        $this->timestamp = $row['timestamp'];
        $this->width = $row['width'];
        $this->height = $row['height'];
    }

    public function setAlbums(array $albums)
    {
        $this->albums = $albums;
    }

    public function setFeatured()
    {
        $this->featured = true;
    }
}