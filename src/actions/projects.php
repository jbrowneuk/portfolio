<?php

namespace jbrowneuk\actions;

/**
 * An action that fetches current projects from github and renders them as a list
 */
class Projects
{
    public function __construct(private readonly \jbrowneuk\interfaces\IRenderer $renderer) {}

    public function __invoke()
    {
        $projects = \jbrowneuk\services\GithubProjects::getProjectsFromGithub();
        $this->renderer->setPageId('projects');
        $this->renderer->assign('projects', $projects);
        $this->renderer->displayPage('projects');
    }
}
