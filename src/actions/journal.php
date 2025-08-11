<?php

namespace jbrowneuk;

/**
 * An action that renders the journal and individual posts
 */
class Journal
{
    public function __construct(private readonly IPostsDBO $postsDBO, private readonly IRenderer $renderer) {}

    public function __invoke(array $pageParams = [])
    {
        $subAction = '_default';
        if (isset($pageParams[0])) {
            $subAction = $pageParams[0];
        }

        // Calculate stale cut-off date
        $years = 2;
        $staleTimestamp = time() - (60 * 60 * 24 * 365 * $years);

        $this->renderer->setPageId('journal');
        $this->renderer->assign('staleTimestamp', $staleTimestamp);

        switch ($subAction) {
            case 'post':
                $this->renderSinglePost($pageParams);
                break;
            default:
                $this->renderPostList($pageParams);
                break;
        }
    }

    /**
     * Renders the post list page
     */
    private function renderPostList(array $pageParams): void
    {
        $page = parsePageNumber($pageParams);
        $tag = getValueFromPageParams($pageParams, 'tag');

        $posts = $this->postsDBO->getPosts($page, $tag);
        $basePagination = $this->postsDBO->getPostPaginationData($tag);

        // Fill out pagination data
        $pagination = ['page' => $page, ...$basePagination];

        // Add tag data to template, if set
        if ($tag !== null) {
            $this->renderer->assign('tag', $tag);
            $pagination['prefix'] = "/tag/$tag";
        }

        // Render page
        $this->renderer->assign('posts', $posts);
        $this->renderer->assign('pagination', $pagination);
        $this->renderer->displayPage('post-list');
    }

    /**
     * Renders a single post page
     */
    private function renderSinglePost(array $pageParams): void
    {
        // Since the first page parameter is 'post', next element should be ID
        if (isset($pageParams[1])) {
            $post = $this->postsDBO->getPost($pageParams[1]);
            $this->renderer->assign('post', $post);
        }

        $this->renderer->displayPage('single-post');
    }
}
