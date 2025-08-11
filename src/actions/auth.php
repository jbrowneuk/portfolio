<?php

namespace jbrowneuk;

/**
 * An action that controls logging in and out of the site's admin functionality
 */
class Auth
{
    public function __construct(private readonly IAuthentication $auth, private readonly IRenderer $renderer) {}

    public function __invoke(array $pageParams = [])
    {
        $this->renderer->setPageId('auth');

        $isLogout = array_search('logout', $pageParams, true) !== false;

        if ($isLogout) {
            $this->handleLogout();
        } else {
            $this->handleLogin();
        }
    }

    /**
     * Handles the login page request
     */
    private function handleLogin(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->auth->login($_POST['username'], $_POST['password']);
            if (!$result) {
                $this->renderer->assign('loginError', true);
            }
        }

        if ($this->auth->isAuthenticated()) {
            $this->renderer->redirectTo('editor');
            return;
        }

        $this->renderer->displayPage('login');
    }

    /**
     * Handles the logout page request
     */
    private function handleLogout(): void
    {
        $this->auth->logout();
        $this->renderer->redirectTo('');
    }
}
