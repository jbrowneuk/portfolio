<?php

namespace jbrowneuk;

class Editor
{
    public function __construct(private readonly IPostsDBO $postsDBO, private readonly IAuthentication $auth, private readonly IRenderer $renderer) {}

    public function __invoke(array $pageParams = [])
    {
        if (!$this->auth->isAuthenticated()) {
            $this->renderer->redirectTo('auth');
            return;
        }

        $this->renderer->setPageId('admin');
        
        $subAction = '_default';
        if (isset($pageParams[0])) {
            $subAction = $pageParams[0];
        }

        switch ($subAction) {
            default:
                $this->renderPostList($pageParams);
                break;
        }
    }

    private function renderPostList(array $pageParams) {
        $this->postsDBO->setPostsPerPage(16);
        $this->postsDBO->showDrafts(true);

        $page = parsePageNumber($pageParams);

        $posts = $this->postsDBO->getPosts($page);
        $basePagination = $this->postsDBO->getPostPaginationData();

        // Fill out pagination data
        $pagination = ['page' => $page, ...$basePagination];
        $this->renderer->assign('pagination', $pagination);

        $this->renderer->assign('posts', $posts);
        $this->renderer->displayPage('editor');
    }
}
