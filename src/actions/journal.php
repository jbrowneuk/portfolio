<?php
namespace jbrowneuk;

class Journal implements Page {
    public function render($pdo, $renderer) {
        try {
            $posts = get_posts($pdo);
        } catch (\PDOException $ex) {
            die($ex->getMessage());
        }
    
        $renderer->setPageId('journal');
        $renderer->assign('posts', $posts);
        $renderer->displayPage('post-list');
    }
}