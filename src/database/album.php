<?php

namespace jbrowneuk;

class AlbumDBO implements IAlbumDBO
{
    // One image on each page is promoted/made large, therefore it takes up two
    // spaces. This is an odd number due to that.
    const int IMAGES_PER_PAGE = 11;

    // The album_id of the featured album
    const string FEATURED_ALBUM_ID = 'featured';

    // Columns to take from a database row to make up album information
    const array ALBUM_COLUMNS = ['album_id', 'name', 'description'];

    // Columns to take from a database row to make up image information
    const array IMAGE_COLUMNS = ['image_id', 'title', 'filename', 'description', 'timestamp', 'width', 'height'];

    /**
     * Constructs an instance of the Album Database Object
     *
     * @param \PDO $pdo a connected PDO object
     */
    public function __construct(private readonly \PDO $pdo) {}

    public function getAlbums()
    {
        $statement = $this->pdo->query('SELECT * FROM albums');

        $albums = [];
        while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $albums[] = $this->generateAlbumData($row);
        }

        return $albums;
    }

    public function getAlbum(string $albumId)
    {
        $statement = $this->pdo->prepare('SELECT * FROM albums WHERE album_id = :albumId LIMIT 1');
        $statement->execute(['albumId' => $albumId]);
        $row = $statement->fetch(\PDO::FETCH_ASSOC);
        return $this->generateAlbumData($row);
    }

    public function getAlbumPaginationData(string $albumId)
    {
        return array(
            'items_per_page' => self::IMAGES_PER_PAGE,
            'total_items' => $this->getImageCountForAlbum($albumId)
        );
    }

    public function getImagesForAlbum(string $albumId, int $page = 1)
    {
        $offset = ($page > 0 ? $page - 1 : 1) * self::IMAGES_PER_PAGE;

        $statement = $this->pdo->prepare('SELECT *
            FROM image_albums
            JOIN images ON image_albums.image_id = images.image_id
            WHERE image_albums.album_id = :albumName
            ORDER BY images.timestamp DESC
            LIMIT :offset, :limit');
        $statement->execute(['albumName' => $albumId, 'offset' => $offset, 'limit' => self::IMAGES_PER_PAGE]);

        $images = [];
        while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $images[] = $this->generateImageData($row);
        }

        return $images;
    }

    public function getAlbumsForImage(int $imageId)
    {
        $statement = $this->pdo->prepare('SELECT *
            FROM image_albums
            JOIN albums ON image_albums.album_id = albums.album_id
            WHERE image_albums.image_id = :imageId');
        $statement->execute(['imageId' => $imageId]);

        $albums = [];
        while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $album = [];
            foreach (self::ALBUM_COLUMNS as $column) {
                $album[$column] = $row[$column];
            }

            $albums[$album['album_id']] = $album;
        }

        return $albums;
    }

    public function getImage(int $imageId)
    {
        $statement = $this->pdo->prepare('SELECT * FROM images WHERE image_id = :imageId');
        $statement->execute(['imageId' => $imageId]);
        $row = $statement->fetch(\PDO::FETCH_ASSOC);

        return $this->generateImageData($row);
    }

    /**
     * Constructs image data from a database row
     *
     * @param array $row the database row
     *
     * @return array image data array
     */
    private function generateImageData(array $row)
    {
        $image = [];
        foreach (self::IMAGE_COLUMNS as $column) {
            $image[$column] = $row[$column];
        }

        $image['albums'] = $this->getAlbumsForImage($image['image_id']);

        // Calculate whether image is in featured album
        if (isset($image['albums'][self::FEATURED_ALBUM_ID])) {
            $image['featured'] = true;
        }

        return $image;
    }

    /**
     * Constructs album data from a database row
     *
     * @param array $row the database row
     *
     * @return array album data array
     */
    private function generateAlbumData(array $row)
    {
        $album = [];
        foreach (self::ALBUM_COLUMNS as $column) {
            $album[$column] = $row[$column];
        }

        $album['image_count'] = $this->getImageCountForAlbum($album['album_id']);

        return $album;
    }

    /**
     * Gets the count of images in the specified album
     *
     * @param string $albumId the ID of the album to get the image count from
     *
     * @return int total count of images in the specified album
     */
    private function getImageCountForAlbum(string $albumId)
    {
        $statement = $this->pdo->prepare('SELECT count(image_id) AS total FROM image_albums WHERE album_id = :albumId');
        $statement->execute(['albumId' => $albumId]);
        $row = $statement->fetch(\PDO::FETCH_ASSOC);
        return $row['total'];
    }
}
