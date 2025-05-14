<?php

namespace jbrowneuk;

class Journal implements Action
{
    public function render(\PDO $pdo, PortfolioRenderer $renderer, array $pageParams)
    {
        $page = parsePageNumber($pageParams);

        $postsDBO = posts_dbo_factory($pdo);
        $posts = $postsDBO->getPosts($page);
        $basePagination = $postsDBO->getPostPaginationData();

        // Fill out pagination data
        $pagination = ['page' => $page, ...$basePagination];

        // Calculate stale cut-off date
        $years = 2;
        $staleTimestamp = time() - (60 * 60 * 24 * 365 * $years);

        // Render page
        $renderer->setPageId('journal');
        $renderer->assign('posts', $posts);
        $renderer->assign('pagination', $pagination);
        $renderer->assign('staleTimestamp', $staleTimestamp);
        $renderer->displayPage('post-list');
    }
}
