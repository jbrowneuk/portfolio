<?php

namespace jbrowneuk;

class Editor implements IAction
{
    protected array $editorRoutes = [
        'post' => PostEditor::class
    ];

    public function render(\PDO $pdo, PortfolioRenderer $renderer, array $pageParams): void
    {
        $auth = new Authentication($pdo);

        if (!$auth->isAuthenticated()) {
            $renderer->redirectTo('auth');
            return;
        }

        $renderer->setPageId('admin');

        $subAction = '_default';
        if (isset($pageParams[0])) {
            $subAction = $pageParams[0];
        }

        if (!array_key_exists($subAction, $this->editorRoutes)) {
            print 'No default action yet';
            return;
        }

        $postsDBO = posts_dbo_factory($pdo);

        $editorActionClass = $this->editorRoutes[$subAction];
        $editorAction = new $editorActionClass();
        $editorAction->render($postsDBO, $renderer, $pageParams);
    }
}
