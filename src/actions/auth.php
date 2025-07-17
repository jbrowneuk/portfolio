<?php

namespace jbrowneuk;

/**
 * An action that controls logging in and out of the site's admin functionality
 */
class Auth implements Action
{
    public function render(\PDO $pdo, PortfolioRenderer $renderer, array $pageParams)
    {
        $renderer->setPageId('auth');

        $auth = new Authentication($pdo);
        $isLogout = array_search('logout', $pageParams, true) !== false;

        if ($isLogout) {
            $this->handleLogout($auth, $renderer);
        } else {
            $this->handleLogin($auth, $renderer);
        }
    }

    /**
     * Handles the login page request
     */
    private function handleLogin(Authentication $auth, PortfolioRenderer $renderer): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $auth->login($_POST['username'], $_POST['password']);
            if (!$result) {
                $renderer->assign('loginError', true);
            }
        }

        if ($auth->isAuthenticated()) {
            $renderer->redirectTo('editor');
            return;
        }

        $renderer->displayPage('login');
    }

    /**
     * Handles the logout page request
     */
    private function handleLogout(Authentication $auth, PortfolioRenderer $renderer): void
    {
        $auth->logout();
        $renderer->redirectTo('');
    }
}
