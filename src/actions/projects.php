<?php

namespace jbrowneuk;

/**
 * An action that fetches current projects from github and renders them as a list
 */
class Projects
{
    public function __construct(private readonly IRenderer $renderer) {}

    public function __invoke()
    {
        $projects = get_projects_from_github();
        $this->renderer->setPageId('projects');
        $this->renderer->assign('projects', $projects);
        $this->renderer->displayPage('projects');
    }
}
