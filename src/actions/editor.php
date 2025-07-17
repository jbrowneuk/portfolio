<?php

namespace jbrowneuk;

class Editor implements Action
{
    public function render(\PDO $pdo, PortfolioRenderer $renderer, array $pageParams)
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
