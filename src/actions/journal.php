<?php

namespace jbrowneuk;

class Journal implements Page
{
    public static function modifier_pagination(array $pagination)
    {
        $totalPages = ceil($pagination['total_items'] / $pagination['items_per_page']);
        $pages = range(1, $totalPages);
        return $pages;
    }

    public function render(\PDO $pdo, PortfolioRenderer $renderer, array $pageParams)
    {
        $page = $this->calculatePage($pageParams);
        $posts = get_posts($pdo, $page);
        $pagination = get_post_pagination_data($pdo);

        $renderer->registerPlugin(\Smarty\Smarty::PLUGIN_MODIFIER, 'pagination', '\jbrowneuk\Journal::modifier_pagination');

        $renderer->setPageId('journal');
        $renderer->assign('posts', $posts);
        $renderer->assign('pagination', ['page' => $page, ...$pagination]);
        $renderer->displayPage('post-list');
    }

    // [TODO] convert to helper function so all pages can use it
    private function calculatePage(array $pageParams) {
        $index = array_find_key($pageParams, fn($item) => $item === 'page');

        // Calculate whether the next param after 'page' is numeric
        if (array_key_exists($index + 1, $pageParams) && is_numeric($pageParams[$index + 1])) {
            return (int)$pageParams[$index + 1];
        }

        return 1;
    }
}
