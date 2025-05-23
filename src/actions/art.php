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

        $albumDBO = album_dbo_factory($pdo);

        switch ($subAction) {
            case 'albums':
                $this->renderAlbumList($albumDBO, $renderer);
                break;

            case 'view':
                $this->renderImageView($albumDBO, $renderer, $pageParams);
                break;

            default:
                $this->renderAlbumPage($albumDBO, $renderer, $pageParams);
                break;
        }
    }

    private function renderAlbumPage(IAlbumDBO $dbo, PortfolioRenderer $renderer, array $params)
    {
        $page = parsePageNumber($params);

        $albumId = 'featured';
        $album = $dbo->getAlbum($albumId);
        $pagination = $dbo->getAlbumPaginationData($albumId);
        $images = $dbo->getImagesForAlbum($albumId, $page);
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

    private function renderImageView(IAlbumDBO $dbo, PortfolioRenderer $renderer, array $params)
    {
        if (isset($params[0]) && is_numeric($params[0])) {
            $imageId = (int)$params[0];
            $image = $dbo->getImage($imageId);
            $renderer->assign('image', $image);
        }

        $renderer->displayPage('image');
    }

    private function renderAlbumList(IAlbumDBO $dbo, PortfolioRenderer $renderer)
    {
        $albums = $dbo->getAlbums();
        $renderer->assign('albums', $albums);
        $renderer->displayPage('album-list');
    }
}
