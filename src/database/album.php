<?php

namespace jbrowneuk;

// One image on each page is promoted/made large, therefore it takes up two
// spaces. This is an odd number due to that.
const IMAGES_PER_PAGE = 11;

// The album_id of the featured album
const FEATURED_ALBUM_ID = 'featured';

// Columns to take from a database row to make up album information
$albumColumns = ['album_id', 'name', 'description'];

// Columns to take from a database row to make up image information
$imageColumns = ['image_id', 'title', 'filename', 'description', 'timestamp', 'width', 'height'];

/**
 * Gets all albums from the database
 *
 * @param \PDO $pdo a connected PDO object
 *
 * @return array all album data
 */
function get_albums(\PDO $pdo)
{
    global $albumColumns;
    $statement = $pdo->query('SELECT * FROM albums');

    $albums = [];
    while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
        $album = [];
        foreach ($albumColumns as $column) {
            $album[$column] = $row[$column];
        }

        $album['image_count'] = get_image_count_for_album($pdo, $album['album_id']);

        $albums[] = $album;
    }

    return $albums;
}

/**
 * Gets a specific album from the database
 *
 * @param \PDO $pdo a connected PDO object
 * @param string $albumId the ID of the album to fetch
 *
 * @return array album data for the specified album
 */
function get_album(\PDO $pdo, string $albumId)
{
    global $albumColumns;

    $statement = $pdo->prepare('SELECT * FROM albums WHERE album_id = :albumid LIMIT 1');
    $statement->execute(['albumid' => $albumId]);
    $row = $statement->fetch(\PDO::FETCH_ASSOC);
    $album = [];
    foreach ($albumColumns as $column) {
        $album[$column] = $row[$column];
    }

    return $album;
}

/**
 * Gets the count of images in the specified album
 *
 * @param \PDO $pdo a connected PDO object
 * @param string $albumId the ID of the album to get the image count from
 *
 * @return int total count of images in the specified album
 */
function get_image_count_for_album(\PDO $pdo, string $albumId)
{
    $statement = $pdo->prepare('SELECT count(image_id) AS total FROM image_albums WHERE album_id = :albumid');
    $statement->execute(['albumid' => $albumId]);
    $row = $statement->fetch(\PDO::FETCH_ASSOC);
    return $row['total'];
}

/**
 * Constructs image data from a database row
 *
 * @param \PDO $pdo a connected PDO object
 * @param array $row the database row
 *
 * @return array image data array
 */
function generate_image_data(\PDO $pdo, array $row)
{
    global $imageColumns;

    $image = [];
    foreach ($imageColumns as $column) {
        $image[$column] = $row[$column];
    }

    $image['albums'] = get_albums_for_image($pdo, $image['image_id']);

    // Calculate whether image is in featured album
    if (isset($image['albums'][FEATURED_ALBUM_ID])) {
        $image['featured'] = true;
    }

    return $image;
}

/**
 * Gets a page of image data for images in a specified album
 *
 * @param \PDO $pdo a connected PDO object
 * @param string $albumId the ID of the album that contains the images
 * @param int page the page to fetch
 *
 * @return array a page of image data for the specified album
 */
function get_images_for_album(\PDO $pdo, string $albumId, int $page = 1)
{
    if ($albumId === null) {
        return [];
    }

    $offset = ($page > 0 ? $page - 1 : 1) * POSTS_PER_PAGE;

    $statement = $pdo->prepare('SELECT *
        FROM image_albums
        JOIN images ON image_albums.image_id = images.image_id
        WHERE image_albums.album_id = :albumname
        ORDER BY images.timestamp DESC
        LIMIT :offset, :limit');
    $statement->execute(['albumname' => $albumId, 'offset' => $offset, 'limit' => IMAGES_PER_PAGE]);

    $images = [];
    while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
        $images[] = generate_image_data($pdo, $row);
    }

    return $images;
}

/**
 * Gets the album data for albums containing a specific image
 *
 * @param \PDO $pdo a connected PDO object
 * @param int $imageId the ID of the image to get album data for
 *
 * @return array album data for the albums containing the specified image
 */
function get_albums_for_image(\PDO $pdo, int $imageId)
{
    global $albumColumns;

    $statement = $pdo->prepare('SELECT *
        FROM image_albums
        JOIN albums ON image_albums.album_id = albums.album_id
        WHERE image_albums.image_id = :imageid');
    $statement->execute(['imageid' => $imageId]);

    $albums = [];
    while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
        $album = [];
        foreach ($albumColumns as $column) {
            $album[$column] = $row[$column];
        }

        $albums[$album['album_id']] = $album;
    }

    return $albums;
}

/**
 * Gets the image data for a specific image
 *
 * @param \PDO $pdo a connected PDO object
 * @param int $imageId the ID of the image to get the data for
 *
 * @return array image data for the specified image
 */
function get_image(\PDO $pdo, int $imageId)
{
    global $imageColumns;

    $statement = $pdo->prepare('SELECT * FROM images WHERE image_id = :imageid');
    $statement->execute(['imageid' => $imageId]);
    $row = $statement->fetch(\PDO::FETCH_ASSOC);

    return generate_image_data($pdo, $row);
}
