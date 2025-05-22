<?php

namespace jbrowneuk;

class Journal implements Action
{
    public function render(\PDO $pdo, PortfolioRenderer $renderer, array $pageParams)
    {
        $subAction = '_default';
        if (isset($pageParams[0])) {
            $subAction = array_shift($pageParams);
        }

        $postsDBO = posts_dbo_factory($pdo);

        // Calculate stale cut-off date
        $years = 2;
        $staleTimestamp = time() - (60 * 60 * 24 * 365 * $years);

        $renderer->setPageId('journal');
        $renderer->assign('staleTimestamp', $staleTimestamp);

        switch ($subAction) {
            case 'post':
                $this->renderSinglePost($postsDBO, $renderer, $pageParams);
                break;
            default:
                $this->renderPostList($postsDBO, $renderer, $pageParams);
                break;
        }
    }

    private function renderPostList(IPostsDBO $postsDBO, PortfolioRenderer $renderer, array $pageParams)
    {
        $page = parsePageNumber($pageParams);

        $posts = $postsDBO->getPosts($page);
        $basePagination = $postsDBO->getPostPaginationData();

        // Fill out pagination data
        $pagination = ['page' => $page, ...$basePagination];

        // Render page
        $renderer->assign('posts', $posts);
        $renderer->assign('pagination', $pagination);
        $renderer->displayPage('post-list');
    }

    private function renderSinglePost(IPostsDBO $postsDBO, PortfolioRenderer $renderer, array $pageParams)
    {
        // Since the first page parameter is 'post' and that's removed with array_shift, next element should be ID
        if (isset($pageParams[0])) {
            $post = $postsDBO->getPost($pageParams[0]);
            $renderer->assign('post', $post);
        }

        $renderer->displayPage('single-post');
    }
}
