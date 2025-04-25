<?php
namespace jbrowneuk;

class Projects implements Page {
    public function render($pdo, $renderer) {
        $renderer->setPageId('projects');
        $renderer->displayPage('projects');
    }
}