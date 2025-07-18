<?php

namespace jbrowneuk;

class Editor implements IAction
{
    public function render(\PDO $pdo, PortfolioRenderer $renderer, array $pageParams): void
    {
        $auth = new Authentication($pdo);

        if (!$auth->isAuthenticated()) {
            $renderer->redirectTo('auth');
            return;
        }

        $renderer->setPageId('admin');
        $renderer->displayPage('editor');
    }
}
