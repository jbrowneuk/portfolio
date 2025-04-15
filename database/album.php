<?php
namespace jbrowneuk;

const FEATURED_FOLDER_ID = '1';
const LATEST_ALBUM_ID = '0';
const IMAGES_PER_PAGE = 11; // By default, one image on each page is promoted/made larg

// Cached album data to prevent duplicate lookups when generating thumbnails
$albumCache = [];

function get_album_by_id($pdo, $albumId) {
    global $albumCache;

    if (isset($albumCache[$albumId])) {
        return $albumCache[$albumId];
    }

    $statement = $pdo->prepare('SELECT * from oiam_albums WHERE album_id = :albumId LIMIT 1');
    $statement->execute(['albumId' => $albumId]);
    $row = $statement->fetch(\PDO::FETCH_ASSOC);
    if ($row === false) {
        return null;
    }

    $album = [];
    $cols = ['album_id', 'title', 'name', 'description'];
    foreach ($cols as $column) {
        $album[$column] = $row[$column];
    }

    // Cache album for future use
    $albumCache[$albumId] = $album;
    return $album;
}

function get_images_for_album($pdo, $albumName = null, $page = 1) {
    if ($albumName === null) {
        $albumName = 'unknown';
    }

    // [TODO] make configurable
    $statement = $pdo->prepare('SELECT * FROM oiam_gallery WHERE galleries LIKE :galleries ORDER BY image_date DESC LIMIT :limit');
    $statement->execute(['galleries' => '%1%', 'limit' => IMAGES_PER_PAGE]);

    $images = [];
    while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
        $image = [];
        $cols = ['image_id', 'galleries', 'file', 'title', 'description', 'image_date', 'width', 'height'];
        foreach ($cols as $column) {
            $image[$column] = $row[$column];
        }

        // Populate allbum information
        $albumIds = explode(' ', $image['galleries']);
        $image['albums'] = [];
        foreach ($albumIds as $id) {
          // Set featured flag if in featured album
          if ($id === FEATURED_FOLDER_ID) {
            $image['featured'] = true;
          }

          $image['albums'][$id] = get_album_by_id($pdo, $id);
        }

        $images[] = $image;
    }

    return $images;
}