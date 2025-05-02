<?php

namespace jbrowneuk;

function modifier_album_names($input)
{
    if (!is_array($input)) {
        return $input;
    }

    $titles = array_map(function ($album) {
        return $album['name'];
    }, $input);
    return implode(', ', $titles);
}

class Art implements Page
{
    public function render($pdo, $renderer, $pageParams)
    {
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
            default:
                $this->renderAlbumPage($pdo, $renderer);
                break;
        }
    }

    private function renderAlbumPage($pdo, $renderer)
    {
        $page = 1;
        $albumId = 'featured';
        $album = get_album($pdo, $albumId);
        $imageCount = get_image_count_for_album($pdo, $albumId);
        $images = get_images_for_album($pdo, $albumId, $page);

        // Seed random number generator to get same promoted image per album page
        $pageImageCount = count($images); // If there's less than NUM_IMAGES on a page
        $seed = intval($album['album_id'] . $pageImageCount . $page, 36);
        mt_srand($seed);
        $promotedIndex = mt_rand(0, $pageImageCount);

        $renderer->assign('album', $album);
        $renderer->assign('promotedImageIndex', $promotedIndex);
        $renderer->assign('images', $images);
        $renderer->assign('totalImageCount', $imageCount);
        $renderer->displayPage('album');
    }
}
