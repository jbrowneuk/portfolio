<?php

namespace jbrowneuk;

require_once 'src/model/album.php';
require_once 'src/model/image.php';

const MOCK_PER_PAGE = 5;
const MOCK_IMAGE_COUNT = 1024;
const MOCK_ALBUM_1_ROW = ['album_id' => 'id1', 'name' => 'a1', 'description' => 'desc1'];
const MOCK_ALBUM_2_ROW = ['album_id' => 'id2', 'name' => 'a2', 'description' => 'desc2'];
const MOCK_IMAGE_HORIZ_ROW = [
    'image_id' => 1,
    'title' => 'i',
    'filename' => '1.jpg',
    'description' => 'd1',
    'timestamp' => 0,
    'width' => 100,
    'height' => 50
];
const MOCK_IMAGE_VERT_ROW = [
    'image_id' => 2,
    'title' => '2',
    'filename' => '2.jpg',
    'description' => 'd2',
    'timestamp' => 0,
    'width' => 50,
    'height' => 100
];

const MOCK_ALBUM_1 = new Album(MOCK_ALBUM_1_ROW);
const MOCK_ALBUM_2 = new Album(MOCK_ALBUM_2_ROW);
const MOCK_IMAGE_HORIZ = new Image(MOCK_IMAGE_HORIZ_ROW);
const MOCK_IMAGE_VERT = new Image(MOCK_IMAGE_VERT_ROW);
