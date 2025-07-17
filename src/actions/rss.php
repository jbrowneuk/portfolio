<?php

namespace jbrowneuk;

class RSS implements IAction
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

    public function render(\PDO $pdo, PortfolioRenderer $renderer, array $pageParams): void
    {
        $renderer->setPageId('rss');

        $renderer->registerPlugin(\Smarty\Smarty::PLUGIN_MODIFIER, 'readTime', '\jbrowneuk\calculateReadTime');

        $postsDBO = posts_dbo_factory($pdo);
        $posts = $postsDBO->getPosts();
        $renderer->assign('posts', $posts);

        header('Content-Type: application/rss+xml; charset=utf-8');
        $renderer->displayPage('rss');
    }
}
