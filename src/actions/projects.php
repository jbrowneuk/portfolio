<?php
namespace jbrowneuk;

class Projects implements Page {
    public function render($pdo, $renderer, $pageParams) {
        $renderer->setPageId('projects');
        $renderer->displayPage('projects');
    }
}