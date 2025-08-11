<?php

namespace jbrowneuk;

class Editor
{
    public function __construct(private readonly IAuthentication $auth, private readonly IRenderer $renderer) {}

    public function __invoke()
    {
        if (!$this->auth->isAuthenticated()) {
            $this->renderer->redirectTo('auth');
            return;
        }

        $this->renderer->setPageId('admin');
        $this->renderer->displayPage('editor');
    }
}
