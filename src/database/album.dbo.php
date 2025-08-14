<?php

namespace jbrowneuk;

final class AlbumSQL
{
    private const SELECT_ALBUMS_ROOT = 'SELECT * FROM albums';
    public const SELECT_ALBUMS = self::SELECT_ALBUMS_ROOT;

    public const SELECT_SINGLE_ALBUM = self::SELECT_ALBUMS_ROOT . ' WHERE album_id = :albumId LIMIT 1';

    public const SELECT_IMAGES_IN_ALBUM = 'SELECT *
            FROM image_albums
            JOIN images ON image_albums.image_id = images.image_id
            WHERE image_albums.album_id = :albumName
            ORDER BY images.timestamp DESC
            LIMIT :offset, :limit';

    public const SELECT_ALBUMS_FOR_IMAGE = 'SELECT *
            FROM image_albums
            JOIN albums ON image_albums.album_id = albums.album_id
            WHERE image_albums.image_id = :imageId';

    public const SELECT_SINGLE_IMAGE = 'SELECT * FROM images WHERE image_id = :imageId';

    public const SELECT_IMAGE_COUNT_FOR_ALBUM = 'SELECT count(image_id) AS total FROM image_albums WHERE album_id = :albumId';
}

class AlbumDBO implements IAlbumDBO
{
    // One image on each page is promoted/made large, therefore it takes up two
    // spaces. This is an odd number due to that.
    const int IMAGES_PER_PAGE = 11;

    // The album_id of the featured album
    const string FEATURED_ALBUM_ID = 'featured';

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
        $statement = $this->pdo->query(AlbumSQL::SELECT_ALBUMS);

        $albums = [];
        while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $albums[] = $this->generateAlbumData($row);
        }

        return $albums;
    }

    public function getAlbum(string $albumId): ?Album
    {
        $statement = $this->pdo->prepare(AlbumSQL::SELECT_SINGLE_ALBUM);
        $statement->execute(['albumId' => $albumId]);
        $row = $statement->fetch(\PDO::FETCH_ASSOC);
        if ($row === false) {
            return null;
        }

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

        $statement = $this->pdo->prepare(AlbumSQL::SELECT_IMAGES_IN_ALBUM);
        $statement->execute(['albumName' => $albumId, 'offset' => $offset, 'limit' => self::IMAGES_PER_PAGE]);

        $images = [];
        while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $images[] = $this->generateImageData($row);
        }

        return $images;
    }

    public function getAlbumsForImage(int $imageId)
    {
        $statement = $this->pdo->prepare(AlbumSQL::SELECT_ALBUMS_FOR_IMAGE);
        $statement->execute(['imageId' => $imageId]);

        $albums = [];
        while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $album = new Album($row);
            $albums[$album->id] = $album;
        }

        return $albums;
    }

    public function getImage(int $imageId)
    {
        $statement = $this->pdo->prepare(AlbumSQL::SELECT_SINGLE_IMAGE);
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
     * @return Album album data array
     */
    private function generateAlbumData(array $row): Album
    {
        $album = new Album($row);
        $album->setImageCount($this->getImageCountForAlbum($album->id));

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
        $statement = $this->pdo->prepare(AlbumSQL::SELECT_IMAGE_COUNT_FOR_ALBUM);
        $statement->execute(['albumId' => $albumId]);
        $row = $statement->fetch(\PDO::FETCH_ASSOC);
        return $row['total'];
    }
}
