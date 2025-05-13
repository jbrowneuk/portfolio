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

class Art implements Action
{
    public function render(\PDO $pdo, PortfolioRenderer $renderer, array $pageParams)
    {
        $subAction = '_default';
        if (isset($pageParams[0])) {
            $subAction = array_shift($pageParams);
        }

        // [TODO] use subdirectory from config file
        $renderer->assign('imageRoot', '/media/art/');
        $renderer->assign('thumbDir', 'thumbnails/');
        $renderer->assign('imageDir', 'images/');
        $renderer->assign('iconDir', 'icons/');
        $renderer->setPageId('art');

        // Album name formatter
        $renderer->registerPlugin(\Smarty\Smarty::PLUGIN_MODIFIER, 'albumNames', '\jbrowneuk\modifier_album_names');

        switch ($subAction) {
            case 'albums':
                $this->renderAlbumList($pdo, $renderer);
                break;

            case 'view':
                $this->renderImageView($pdo, $renderer, $pageParams);
                break;

            default:
                $this->renderAlbumPage($pdo, $renderer, $pageParams);
                break;
        }
    }

    private function renderAlbumPage(\PDO $pdo, PortfolioRenderer $renderer, array $params)
    {
        $page = parsePageNumber($params);
        $albumId = 'featured';
        $album = get_album($pdo, $albumId);
        $pagination = get_album_pagination_data($pdo, $albumId);
        $images = get_images_for_album($pdo, $albumId, $page);
        $urlPrefix = "/album/{$album['album_id']}";

        // Seed random number generator to get same promoted image per album page
        $pageImageCount = count($images); // If there's less than NUM_IMAGES on a page
        $seed = intval($album['album_id'] . $pageImageCount . $page, 36);
        mt_srand($seed);
        $promotedIndex = mt_rand(0, $pageImageCount);

        $renderer->assign('album', $album);
        $renderer->assign('promotedImageIndex', $promotedIndex);
        $renderer->assign('images', $images);
        $renderer->assign('pagination', ['page' => $page, 'prefix' => $urlPrefix, ...$pagination]);
        $renderer->assign('totalImageCount', $pagination['total_items']);
        $renderer->displayPage('album');
    }

    private function renderImageView(\PDO $pdo, PortfolioRenderer $renderer, array $params)
    {
        if (isset($params[0]) && is_numeric($params[0])) {
            $imageId = (int)$params[0];
            $image = get_image($pdo, $imageId);
            $renderer->assign('image', $image);
        }

        $renderer->displayPage('image');
    }

    private function renderAlbumList(\PDO $pdo, PortfolioRenderer $renderer)
    {
        $albums = get_albums($pdo);
        $renderer->assign('albums', $albums);
        $renderer->displayPage('album-list');
    }
}
