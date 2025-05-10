<?php

namespace jbrowneuk;

class Journal implements Action
{
    

    public function render(\PDO $pdo, PortfolioRenderer $renderer, array $pageParams)
    {
        $page = parsePageNumber($pageParams);
        $posts = get_posts($pdo, $page);
        $pagination = get_post_pagination_data($pdo);

        $years = 2;
        $staleTimestamp = time() - (60 * 60 * 24 * 365 * $years);

        $renderer->setPageId('journal');
        $renderer->assign('posts', $posts);
        $renderer->assign('pagination', ['page' => $page, ...$pagination]);
        $renderer->assign('staleTimestamp', $staleTimestamp);
        $renderer->displayPage('post-list');
    }
}
