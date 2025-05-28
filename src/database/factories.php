<?php

namespace jbrowneuk;

/**
 * This file is a stop-gap solution while I evaluate Inversion of Control
 * frameworks.
 */

/**
 * Creates an instance of the PostsDBO object
 */
function posts_dbo_factory(\PDO $pdo): IPostsDBO
{
    return new PostsDBO($pdo);
}

/**
 * Creates an instance of the AlbumDBO object
 *
 * @return IAlbumDBO an album database object
 */
function album_dbo_factory(\PDO $pdo): IAlbumDBO
{
    return new AlbumDBO($pdo);
}
