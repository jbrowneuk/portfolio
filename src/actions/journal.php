<?php

namespace jbrowneuk;

class Journal implements Page
{
    public function render(\PDO $pdo, PortfolioRenderer $renderer, array $pageParams)
    {
        $page = $this->calculatePage($pageParams);

        try {
            $posts = get_posts($pdo, $page);
        } catch (\PDOException $ex) {
            die($ex->getMessage());
        }

        $renderer->setPageId('journal');
        $renderer->assign('posts', $posts);
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
