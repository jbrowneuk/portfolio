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

class Art implements IAction
{
    public function render(\PDO $pdo, PortfolioRenderer $renderer, array $pageParams): void
    {
        $subAction = '_default';
        if (isset($pageParams[0])) {
            $subAction = $pageParams[0];
        }

        // [TODO] use subdirectory from config file
        $renderer->assign('imageRoot', '/media/art/');
        $renderer->assign('thumbDir', 'thumbnails/');
        $renderer->assign('imageDir', 'images/');
        $renderer->assign('iconDir', 'icons/');
        $renderer->setPageId('art');

        // Album name formatter
        $renderer->registerPlugin(\Smarty\Smarty::PLUGIN_MODIFIER, 'albumNames', '\jbrowneuk\Art::albumNameConcatenation');

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
        $requestedAlbum = getValueFromPageParams($params, 'album');
        if ($requestedAlbum !== null) {
            $albumId = $requestedAlbum;
        }

        $album = $dbo->getAlbum($albumId);
        if ($album === null) {
            $renderer->displayPage('album');
            return;
        }

        $pagination = $dbo->getAlbumPaginationData($albumId);
        $images = $dbo->getImagesForAlbum($albumId, $page);
        $urlPrefix = "/album/{$album['album_id']}";

        // Seed random number generator to get same promoted image per album page
        $pageImageCount = count($images) - 1; // If there's less than NUM_IMAGES on a page
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
        // Since the first page parameter is 'view', next element should be ID
        $idx = 1;
        if (isset($params[$idx]) && is_numeric($params[$idx])) {
            $imageId = (int)$params[$idx];
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
