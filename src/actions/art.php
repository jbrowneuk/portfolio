<?php

namespace jbrowneuk;

/**
 * An action that is used to render the art gallery and its individual images
 */
class Art implements IAction
{
    /**
     * Smarty modifier to concatenate the list of albums into a comma-separated string
     *
     * @param array $input array of albums
     *
     * @return string the names concatenated with commas
     */
    public static function albumNameFormatter(array $input): string
    {
        $titles = array_map(fn ($album) => $album['name'], $input);
        return implode(', ', $titles);
    }

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
        $renderer->registerPlugin(\Smarty\Smarty::PLUGIN_MODIFIER, 'albumNames', '\jbrowneuk\Art::albumNameFormatter');

        $albumDBO = album_dbo_factory($pdo);

        switch ($subAction) {
            case 'albums':
                $this->renderAlbumList($albumDBO, $renderer);
                break;

            case 'view':
                $this->renderImageView($albumDBO, $renderer, $pageParams);
                break;

            default:
                $this->renderAlbumImagePage($albumDBO, $renderer, $pageParams);
                break;
        }
    }

    /**
     * Renders the image thumbnail grid for an album
     */
    private function renderAlbumImagePage(IAlbumDBO $dbo, PortfolioRenderer $renderer, array $params): void
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

    /**
     * Renders the single image view
     */
    private function renderImageView(IAlbumDBO $dbo, PortfolioRenderer $renderer, array $params): void
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

    /**
     * Renders the list of albums
     */
    private function renderAlbumList(IAlbumDBO $dbo, PortfolioRenderer $renderer): void
    {
        $albums = $dbo->getAlbums();
        $renderer->assign('albums', $albums);
        $renderer->displayPage('album-list');
    }
}
