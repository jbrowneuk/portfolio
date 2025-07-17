<?php

namespace jbrowneuk;

/**
 * An action that fetches current projects from github and renders them as a list
 */
class Projects implements IAction
{
    public function render(\PDO $pdo, PortfolioRenderer $renderer, array $pageParams): void
    {
        $projects = get_projects_from_github();
        $renderer->setPageId('projects');
        $renderer->assign('projects', $projects);
        $renderer->displayPage('projects');
    }
}
