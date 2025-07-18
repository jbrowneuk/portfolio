<?php

namespace jbrowneuk;

/**
 * An action that renders the journal and individual posts
 */
class Journal implements IAction
{
    public function render(\PDO $pdo, PortfolioRenderer $renderer, array $pageParams): void
    {
        $subAction = '_default';
        if (isset($pageParams[0])) {
            $subAction = $pageParams[0];
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

    /**
     * Renders the post list page
     */
    private function renderPostList(IPostsDBO $postsDBO, PortfolioRenderer $renderer, array $pageParams): void
    {
        $page = parsePageNumber($pageParams);
        $tag = getValueFromPageParams($pageParams, 'tag');

        $posts = $postsDBO->getPosts($page, $tag);
        $basePagination = $postsDBO->getPostPaginationData($tag);

        // Fill out pagination data
        $pagination = ['page' => $page, ...$basePagination];

        // Add tag data to template, if set
        if ($tag !== null) {
            $renderer->assign('tag', $tag);
            $pagination['prefix'] = "/tag/$tag";
        }

        // Render page
        $renderer->assign('posts', $posts);
        $renderer->assign('pagination', $pagination);
        $renderer->displayPage('post-list');
    }

    /**
     * Renders a single post page
     */
    private function renderSinglePost(IPostsDBO $postsDBO, PortfolioRenderer $renderer, array $pageParams): void
    {
        // Since the first page parameter is 'post', next element should be ID
        if (isset($pageParams[1])) {
            $post = $postsDBO->getPost($pageParams[1]);
            $renderer->assign('post', $post);
        }

        $renderer->displayPage('single-post');
    }
}
