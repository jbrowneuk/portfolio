<?php

namespace jbrowneuk;

/**
 * An interface encapsulating the Database Object pertaining to album data
 */
interface IAlbumDBO
{
    /**
     * Gets all albums from the database
     *
     * @return array all album data
     */
    public function getAlbums();

    /**
     * Gets a specific album from the database
     *
     * @param string $albumId the ID of the album to fetch
     *
     * @return array album data for the specified album
     */
    public function getAlbum(string $albumId);

    /**
     * Gets the data required for pagination. Returns the expected number of items
     * for a page and the total number of items for an album.
     *
     * @param string $albumId the ID of the album to get the data from
     *
     * @return array pagination data for the specified album
     */
    public function getAlbumPaginationData(string $albumId);

    /**
     * Gets a page of image data for images in a specified album
     *
     * @param string $albumId the ID of the album that contains the images
     * @param int page the page to fetch
     *
     * @return array a page of image data for the specified album
     */
    public function getImagesForAlbum(string $albumId, int $page = 1);

    /**
     * Gets the album data for albums containing a specific image
     *
     * @param int $imageId the ID of the image to get album data for
     *
     * @return array album data for the albums containing the specified image
     */
    public function getAlbumsForImage(int $imageId);

    /**
     * Gets the image data for a specific image
     *
     * @param int $imageId the ID of the image to get the data for
     *
     * @return array image data for the specified image
     */
    public function getImage(int $imageId);
}
