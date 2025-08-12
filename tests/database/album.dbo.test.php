<?php

namespace jbrowneuk;

require_once 'tests/mocks/art.mock.php';

require_once 'src/database/album.dbo.php';

describe('Album Database Object', function () {
    beforeEach(function () {
        $this->mockPdo = \Mockery::mock(\PDO::class);
        $this->albumDbo = new AlbumDBO($this->mockPdo);
    });

    describe('getAlbums', function () {
        beforeEach(function () {
            $this->mockStatement = \Mockery::mock(\PDOStatement::class);
            $this->mockStatement
                ->shouldReceive('fetch')
                ->andReturn([]);
        });

        it('should fetch all albums', function () {
            $this->mockPdo
                ->shouldReceive('query')
                ->with('SELECT * FROM albums')
                ->andReturn($this->mockStatement)
                ->once();

            expect($this->albumDbo->getAlbums())->toBe([]);
        });
    });

    describe('getAlbum', function () {
        beforeEach(function () {
            $this->totalItemCount = 42;

            $albumStatement = \Mockery::mock(\PDOStatement::class);
            $albumStatement
                ->shouldReceive('execute')
                ->with(['albumId' => MOCK_ALBUM_1['album_id']])
                ->once();
            $albumStatement
                ->shouldReceive('fetch')
                ->once()
                ->andReturn(MOCK_ALBUM_1);

            $countStatement = \Mockery::mock(\PDOStatement::class);
            $countStatement
                ->shouldReceive('execute')
                ->with(['albumId' => MOCK_ALBUM_1['album_id']])
                ->once();
            $countStatement
                ->shouldReceive('fetch')
                ->once()
                ->andReturn(['total' => $this->totalItemCount]);

            $this->mockPdo
                ->shouldReceive('prepare')
                ->with('SELECT * FROM albums WHERE album_id = :albumId LIMIT 1')
                ->andReturn($albumStatement)
                ->once();
            $this->mockPdo
                ->shouldReceive('prepare')
                ->with('SELECT count(image_id) AS total FROM image_albums WHERE album_id = :albumId')
                ->andReturn($countStatement)
                ->once();
        });

        it('should fetch album by ID', function () {
            $result = $this->albumDbo->getAlbum(MOCK_ALBUM_1['album_id']);
            expect($result)->toContain(...MOCK_ALBUM_1);
        });

        it('should get image count for specified album', function () {
            $result = $this->albumDbo->getAlbum(MOCK_ALBUM_1['album_id']);
            expect($result['image_count'])->toBe($this->totalItemCount);
        });
    });

    describe('getAlbumPaginationData', function () {
        beforeEach(function () {
            $this->totalItemCount = 42;

            $statement = \Mockery::mock(\PDOStatement::class);
            $statement
                ->shouldReceive('execute')
                ->with(['albumId' => MOCK_ALBUM_1['album_id']])
                ->once();
            $statement
                ->shouldReceive('fetch')
                ->andReturn(['total' => $this->totalItemCount]);

            $this->mockPdo
                ->shouldReceive('prepare')
                ->with('SELECT count(image_id) AS total FROM image_albums WHERE album_id = :albumId')
                ->once()
                ->andReturn($statement);

            $this->paginationData = $this->albumDbo->getAlbumPaginationData(MOCK_ALBUM_1['album_id']);
        });

        it('should return max items per page', function () {
            expect($this->paginationData['items_per_page'])->toBe(AlbumDBO::IMAGES_PER_PAGE);
        });

        it('should return image count for album', function () {
            expect($this->paginationData['total_items'])->toBe($this->totalItemCount);
        });
    });

    describe('getImagesForAlbum', function () {
        it('should query database for image-album link data', function () {
            // Don't care about the statement execution here here so no `with()`
            $statement = \Mockery::mock(\PDOStatement::class);
            $statement
                ->shouldReceive('execute')
                ->once();

            $statement
                ->shouldReceive('fetch')
                ->once()
                ->andReturn([]);

            // [TODO] extract SQL statements to constants file or similar so whitespace isn't the reason a test fails
            $this->mockPdo
                ->shouldReceive('prepare')
                ->with('SELECT *
            FROM image_albums
            JOIN images ON image_albums.image_id = images.image_id
            WHERE image_albums.album_id = :albumName
            ORDER BY images.timestamp DESC
            LIMIT :offset, :limit')
                ->once()
                ->andReturn($statement);

            expect($this->albumDbo->getImagesForAlbum(MOCK_ALBUM_1['album_id']))->toBe([]);
        });

        it('should fetch images for specified album ID', function () {
            $statement = \Mockery::mock(\PDOStatement::class);
            $statement
                ->shouldReceive('execute')
                ->once()
                ->with([
                    'albumName' => MOCK_ALBUM_1['album_id'],
                    'offset' => 0,
                    'limit' => AlbumDBO::IMAGES_PER_PAGE
                ]);

            $statement
                ->shouldReceive('fetch')
                ->once()
                ->andReturn([]);
            $statement
                ->shouldReceive('fetch')
                ->andReturn(FALSE);

            // Don't care about the actual SQL here so no `with()`
            $this->mockPdo
                ->shouldReceive('prepare')
                ->andReturn($statement);

            expect($this->albumDbo->getImagesForAlbum(MOCK_ALBUM_1['album_id']))->toBe([]);
        });

        it('should fetch full image data for specified album ID', function () {
            $imagesInAlbumStatement = \Mockery::mock(\PDOStatement::class);
            $imagesInAlbumStatement
                ->shouldReceive('execute')
                ->once();
            $imagesInAlbumStatement
                ->shouldReceive('fetch')
                ->once()
                ->andReturn(MOCK_IMAGE_HORIZ);
            $imagesInAlbumStatement
                ->shouldReceive('fetch')
                ->andReturn(FALSE);

            $albumsForImageStatement = \Mockery::mock(\PDOStatement::class);
            $albumsForImageStatement
                ->shouldReceive('execute')
                ->once()
                ->with(['imageId' => MOCK_IMAGE_HORIZ['image_id']]);
            $albumsForImageStatement
                ->shouldReceive('fetch')
                ->once()
                ->andReturn(MOCK_ALBUM_1);
            $albumsForImageStatement
                ->shouldReceive('fetch')
                ->andReturn(FALSE);

            // [TODO] extract SQL statements to constants file or similar so whitespace isn't the reason a test fails
            $this->mockPdo
                ->shouldReceive('prepare')
                ->with('SELECT *
            FROM image_albums
            JOIN images ON image_albums.image_id = images.image_id
            WHERE image_albums.album_id = :albumName
            ORDER BY images.timestamp DESC
            LIMIT :offset, :limit')
                ->andReturn($imagesInAlbumStatement);
            
            $this->mockPdo
                ->shouldReceive('prepare')
                ->with('SELECT *
            FROM image_albums
            JOIN albums ON image_albums.album_id = albums.album_id
            WHERE image_albums.image_id = :imageId')
                ->andReturn($albumsForImageStatement);

            $expected = [[...MOCK_IMAGE_HORIZ, 'albums' => [MOCK_ALBUM_1['album_id'] => MOCK_ALBUM_1]]];
            $actual = $this->albumDbo->getImagesForAlbum(MOCK_ALBUM_1['album_id']);

            expect($actual)->toBe($expected);
        });
    });

    describe('getAlbumsForImage', function () {
        it('should query database for image-album link data', function () {
            // Don't care about the statement execution here here so no `with()`
            $statement = \Mockery::mock(\PDOStatement::class);
            $statement
                ->shouldReceive('execute')
                ->once();

            $statement
                ->shouldReceive('fetch')
                ->once()
                ->andReturn([]);

            // [TODO] extract SQL statements to constants file or similar so whitespace isn't the reason a test fails
            $this->mockPdo
                ->shouldReceive('prepare')
                ->with('SELECT *
            FROM image_albums
            JOIN albums ON image_albums.album_id = albums.album_id
            WHERE image_albums.image_id = :imageId')
                ->once()
                ->andReturn($statement);

            expect($this->albumDbo->getAlbumsForImage(1))->toBe([]);
        });

        it('should fetch albums with album_id as key for a specific image ID', function () {
            $expectedImageId = 569;

            $statement = \Mockery::mock(\PDOStatement::class);
            $statement
                ->shouldReceive('execute')
                ->once()
                ->with(['imageId' => $expectedImageId]);
            $statement
                ->shouldReceive('fetch')
                ->once()
                ->andReturn(MOCK_ALBUM_1);
            $statement
                ->shouldReceive('fetch')
                ->andReturn(FALSE);

            // Don't care about the actual SQL here so no `with()`
            $this->mockPdo
                ->shouldReceive('prepare')
                ->once()
                ->andReturn($statement);

            expect($this->albumDbo->getAlbumsForImage($expectedImageId))->toBe([MOCK_ALBUM_1['album_id'] => MOCK_ALBUM_1]);
        });
    });

    describe('getImage', function () {
        it('should query database for specified image', function () {
            $expectedId = 569;

            $statement = \Mockery::mock(\PDOStatement::class);
            $statement
                ->shouldReceive('execute')
                ->once()
                ->with(['imageId' => $expectedId]);
            $statement
                ->shouldReceive('fetch')
                ->andReturn(MOCK_IMAGE_HORIZ);

            $albumsStatement = \Mockery::mock(\PDOStatement::class);
            $albumsStatement
                ->shouldReceive('execute');
            $albumsStatement
                ->shouldReceive('fetch')
                ->andReturn([]);

            $this->mockPdo
                ->shouldReceive('prepare')
                ->once()
                ->with('SELECT * FROM images WHERE image_id = :imageId')
                ->andReturn($statement);

            // Album data query, don't care about this so return empty for anything else
            $this->mockPdo
                ->shouldReceive('prepare')
                ->andReturn($albumsStatement);

            expect($this->albumDbo->getImage($expectedId))->toBe([...MOCK_IMAGE_HORIZ, 'albums' => []]);
        });
    });
});
