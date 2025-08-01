<?php

namespace jbrowneuk;

class Editor implements IAction
{
    public function render(\PDO $pdo, PortfolioRenderer $renderer, array $pageParams): void
    {
        $auth = new Authentication($pdo);

        if (!$auth->isAuthenticated()) {
            $renderer->redirectTo('auth');
            return;
        }

        $renderer->setPageId('admin');

        $subAction = '_default';
        if (isset($pageParams[0])) {
            $subAction = $pageParams[0];
        }

        $postsDBO = posts_dbo_factory($pdo);

        switch ($subAction) {
            default:
                $this->renderPostList($postsDBO, $renderer, $pageParams);
                break;
        }
    }

    private function renderPostList(IPostsDBO $postsDBO, PortfolioRenderer $renderer, array $pageParams) {
        $postsDBO->setPostsPerPage(16);
        $postsDBO->showDrafts(true);

        $page = parsePageNumber($pageParams);

        $posts = $postsDBO->getPosts($page);
        $basePagination = $postsDBO->getPostPaginationData();

        // Fill out pagination data
        $pagination = ['page' => $page, ...$basePagination];
        $renderer->assign('pagination', $pagination);

        $renderer->assign('posts', $posts);
        $renderer->displayPage('editor');
    }
}
