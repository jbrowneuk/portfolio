<?php
namespace jbrowneuk;

function modifier_album_names($input) {
    if (!is_array($input)) {
        return $input;
    }

    $titles = array_map(function($album) { return $album['title']; }, $input);
    return implode(', ', $titles);
}

function renderAlbumPage($pdo, $renderer) {
    $page = 1;
    $albumName = 'Featured';
    $images = get_images_for_album($pdo);
    $imageCount = count($images);

    // Seed random number generator to get same promoted image per album page
    $seed = intval($albumName . $imageCount . $page, 36);
    srand($seed);
    $promotedIndex = rand(0, $imageCount);

    $renderer->assign('albumName', $albumName);
    $renderer->assign('promotedImageIndex', $promotedIndex);
    $renderer->assign('images', $images);
    $renderer->displayPage('album');
}

function renderAction($pdo, $renderer, $pageParams) {
    $subAction = '_default';
    if (isset($pageParams[0])) {
        $subAction = $pageParams[0];
    }

    // [TODO] use subdirectory from config file
    $renderer->assign('imageRoot', '/media/art/');
    $renderer->assign('thumbDir', 'thumbnails/');
    $renderer->assign('imageDir', 'images/');
    $renderer->setPageId('art');

    // Album name formatter
    $renderer->registerPlugin(\Smarty\Smarty::PLUGIN_MODIFIER, 'albumNames', '\jbrowneuk\modifier_album_names');

    switch ($subAction) {
        case 'TBC':
            break;

        default:
            renderAlbumPage($pdo, $renderer);
            break;
    }
}