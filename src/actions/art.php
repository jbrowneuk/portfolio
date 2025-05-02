<?php
namespace jbrowneuk;

class Art implements Page {
    public function render($pdo, $renderer, $pageParams) {
        $renderer->setPageId('art');
        $renderer->assign('albumName', 'Featured');
        $renderer->assign('promotedImageIndex', -1);
        $renderer->assign('images', []);
        $renderer->displayPage('album');
    }
}