<?php

namespace jbrowneuk;

/**
 * An action that is used to render the art gallery and its individual images
 */
class Art
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
        $titles = array_map(fn ($album) => $album->name, $input);
        return implode(', ', $titles);
    }

    public function __construct(private readonly IAlbumDBO $albumsDBO, private readonly IRenderer $renderer) {}

    public function __invoke(array $pageParams = [])
    {
        $subAction = '_default';
        if (isset($pageParams[0])) {
            $subAction = $pageParams[0];
        }

        // [TODO] use subdirectory from config file
        $this->renderer->assign('imageRoot', '/media/art/');
        $this->renderer->assign('thumbDir', 'thumbnails/');
        $this->renderer->assign('imageDir', 'images/');
        $this->renderer->assign('iconDir', 'icons/');
        $this->renderer->setPageId('art');

        // Album name formatter
        $this->renderer->registerPlugin(\Smarty\Smarty::PLUGIN_MODIFIER, 'albumNames', '\jbrowneuk\Art::albumNameFormatter');

        switch ($subAction) {
            case 'albums':
                $this->renderAlbumList();
                break;

            case 'view':
                $this->renderImageView($pageParams);
                break;

            default:
                $this->renderAlbumImagePage($pageParams);
                break;
        }
    }

    /**
     * Renders the image thumbnail grid for an album
     */
    private function renderAlbumImagePage(array $params): void
    {
        $page = parsePageNumber($params);

        $albumId = 'featured';
        $requestedAlbum = getValueFromPageParams($params, 'album');
        if ($requestedAlbum !== null) {
            $albumId = $requestedAlbum;
        }

        $album = $this->albumsDBO->getAlbum($albumId);
        if ($album === null) {
            $this->renderer->displayPage('album');
            return;
        }

        $pagination = $this->albumsDBO->getAlbumPaginationData($albumId);
        $images = $this->albumsDBO->getImagesForAlbum($albumId, $page);
        $urlPrefix = "/album/{$album->id}";

        // Seed random number generator to get same promoted image per album page
        $pageImageCount = count($images) - 1; // If there's less than NUM_IMAGES on a page
        $seed = intval($album->id . $pageImageCount . $page, 36);
        mt_srand($seed);
        $promotedIndex = mt_rand(0, $pageImageCount);

        $this->renderer->assign('album', $album);
        $this->renderer->assign('promotedImageIndex', $promotedIndex);
        $this->renderer->assign('images', $images);
        $this->renderer->assign('pagination', ['page' => $page, 'prefix' => $urlPrefix, ...$pagination]);
        $this->renderer->assign('totalImageCount', $pagination['total_items']);
        $this->renderer->displayPage('album');
    }

    /**
     * Renders the single image view
     */
    private function renderImageView(array $params): void
    {
        // Since the first page parameter is 'view', next element should be ID
        $idx = 1;
        if (isset($params[$idx]) && is_numeric($params[$idx])) {
            $imageId = (int)$params[$idx];
            $image = $this->albumsDBO->getImage($imageId);
            $this->renderer->assign('image', $image);
        }

        $this->renderer->displayPage('image');
    }

    /**
     * Renders the list of albums
     */
    private function renderAlbumList(): void
    {
        $albums = $this->albumsDBO->getAlbums();
        $this->renderer->assign('albums', $albums);
        $this->renderer->displayPage('album-list');
    }
}
