<?php

namespace jbrowneuk;

class RSS
{
    public static function calculateReadTime(string $content): string
    {
        $wordsPerMinute = 200;
        $wordCount = preg_match_all('/\w+/', $content);
        if ($wordCount === false) {
            $wordCount = 0;
        }

        $estimatedTime = round($wordCount / $wordsPerMinute);
        $formattedTime = $estimatedTime > 0 ? $estimatedTime : 'less than a';
        return "$formattedTime minute read";
    }

    public function __construct(private readonly IPostsDBO $postsDBO, private readonly IRenderer $renderer) {
        $this->postsDBO->setPostsPerPage(16);
    }

    public function __invoke()
    {
        $this->renderer->setPageId('rss');
        $this->renderer->registerPlugin(\Smarty\Smarty::PLUGIN_MODIFIER, 'readTime', '\jbrowneuk\RSS::calculateReadTime');

        $posts = $this->postsDBO->getPosts();
        $this->renderer->assign('posts', $posts);

        header('Content-Type: application/rss+xml; charset=utf-8');
        $this->renderer->displayPage('rss');
    }
}
