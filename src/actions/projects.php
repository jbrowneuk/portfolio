<?php

namespace jbrowneuk;

class Projects implements Page
{
    public function render($pdo, $renderer, $pageParams)
    {
        $projects = get_projects_from_github();
        $renderer->setPageId('projects');
        $renderer->assign('projects', $projects);
        $renderer->displayPage('projects');
    }
}
