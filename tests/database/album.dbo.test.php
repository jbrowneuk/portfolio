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
                ->with(AlbumSQL::SELECT_ALBUMS)
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
                ->with(['albumId' => MOCK_ALBUM_1_ROW['album_id']])
                ->once();
            $albumStatement
                ->shouldReceive('fetch')
                ->once()
                ->andReturn(MOCK_ALBUM_1_ROW);

            $countStatement = \Mockery::mock(\PDOStatement::class);
            $countStatement
                ->shouldReceive('execute')
                ->with(['albumId' => MOCK_ALBUM_1_ROW['album_id']])
                ->once();
            $countStatement
                ->shouldReceive('fetch')
                ->once()
                ->andReturn(['total' => $this->totalItemCount]);

            $this->mockPdo
                ->shouldReceive('prepare')
                ->with(AlbumSQL::SELECT_SINGLE_ALBUM)
                ->andReturn($albumStatement)
                ->once();
            $this->mockPdo
                ->shouldReceive('prepare')
                ->with(AlbumSQL::SELECT_IMAGE_COUNT_FOR_ALBUM)
                ->andReturn($countStatement)
                ->once();
        });

        it('should fetch album by ID', function () {
            $expected = clone(MOCK_ALBUM_1);
            $expected->imageCount = $this->totalItemCount;

            $result = $this->albumDbo->getAlbum(MOCK_ALBUM_1_ROW['album_id']);

            expect($result)->toEqual($expected);
        });

        it('should get image count for specified album', function () {
            $result = $this->albumDbo->getAlbum(MOCK_ALBUM_1_ROW['album_id']);
            expect($result->imageCount)->toBe($this->totalItemCount);
        });
    });

    describe('getAlbumPaginationData', function () {
        beforeEach(function () {
            $this->totalItemCount = 42;

            $statement = \Mockery::mock(\PDOStatement::class);
            $statement
                ->shouldReceive('execute')
                ->with(['albumId' => MOCK_ALBUM_1_ROW['album_id']])
                ->once();
            $statement
                ->shouldReceive('fetch')
                ->andReturn(['total' => $this->totalItemCount]);

            $this->mockPdo
                ->shouldReceive('prepare')
                ->with(AlbumSQL::SELECT_IMAGE_COUNT_FOR_ALBUM)
                ->once()
                ->andReturn($statement);

            $this->paginationData = $this->albumDbo->getAlbumPaginationData(MOCK_ALBUM_1_ROW['album_id']);
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

            $this->mockPdo
                ->shouldReceive('prepare')
                ->with(AlbumSQL::SELECT_IMAGES_IN_ALBUM)
                ->once()
                ->andReturn($statement);

            expect($this->albumDbo->getImagesForAlbum(MOCK_ALBUM_1_ROW['album_id']))->toBe([]);
        });

        it('should fetch images for specified album ID', function () {
            $statement = \Mockery::mock(\PDOStatement::class);
            $statement
                ->shouldReceive('execute')
                ->once()
                ->with([
                    'albumName' => MOCK_ALBUM_1_ROW['album_id'],
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

            expect($this->albumDbo->getImagesForAlbum(MOCK_ALBUM_1_ROW['album_id']))->toBe([]);
        });

        it('should fetch full image data for specified album ID', function () {
            $imagesInAlbumStatement = \Mockery::mock(\PDOStatement::class);
            $imagesInAlbumStatement
                ->shouldReceive('execute')
                ->once();
            $imagesInAlbumStatement
                ->shouldReceive('fetch')
                ->once()
                ->andReturn(MOCK_IMAGE_HORIZ_ROW);
            $imagesInAlbumStatement
                ->shouldReceive('fetch')
                ->andReturn(FALSE);

            $albumsForImageStatement = \Mockery::mock(\PDOStatement::class);
            $albumsForImageStatement
                ->shouldReceive('execute')
                ->once()
                ->with(['imageId' => MOCK_IMAGE_HORIZ_ROW['image_id']]);
            $albumsForImageStatement
                ->shouldReceive('fetch')
                ->once()
                ->andReturn(MOCK_ALBUM_1_ROW);
            $albumsForImageStatement
                ->shouldReceive('fetch')
                ->andReturn(FALSE);

            $this->mockPdo
                ->shouldReceive('prepare')
                ->with(AlbumSQL::SELECT_IMAGES_IN_ALBUM)
                ->andReturn($imagesInAlbumStatement);
            
            $this->mockPdo
                ->shouldReceive('prepare')
                ->with(AlbumSQL::SELECT_ALBUMS_FOR_IMAGE)
                ->andReturn($albumsForImageStatement);

            $expectedImage = new Image(MOCK_IMAGE_HORIZ_ROW);
            $expectedImage->setAlbums([MOCK_ALBUM_1_ROW['album_id'] => MOCK_ALBUM_1]);
            $expected = [$expectedImage];

            $actual = $this->albumDbo->getImagesForAlbum(MOCK_ALBUM_1_ROW['album_id']);

            expect($actual)->toEqual($expected);
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
                ->with(AlbumSQL::SELECT_ALBUMS_FOR_IMAGE)
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
                ->andReturn(MOCK_ALBUM_1_ROW);
            $statement
                ->shouldReceive('fetch')
                ->andReturn(FALSE);

            // Don't care about the actual SQL here so no `with()`
            $this->mockPdo
                ->shouldReceive('prepare')
                ->once()
                ->andReturn($statement);

            $expected = [MOCK_ALBUM_1_ROW['album_id'] => MOCK_ALBUM_1];
            expect($this->albumDbo->getAlbumsForImage($expectedImageId))->toEqual($expected);
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
                ->andReturn(MOCK_IMAGE_HORIZ_ROW);

            $albumsStatement = \Mockery::mock(\PDOStatement::class);
            $albumsStatement
                ->shouldReceive('execute');
            $albumsStatement
                ->shouldReceive('fetch')
                ->andReturn([]);

            $this->mockPdo
                ->shouldReceive('prepare')
                ->once()
                ->with(AlbumSQL::SELECT_SINGLE_IMAGE)
                ->andReturn($statement);

            // Album data query, don't care about this so return empty for anything else
            $this->mockPdo
                ->shouldReceive('prepare')
                ->andReturn($albumsStatement);

            $expected = new Image(MOCK_IMAGE_HORIZ_ROW);
            $expected->albums = [];

            expect($this->albumDbo->getImage($expectedId))->toEqual($expected);
        });
    });
});
