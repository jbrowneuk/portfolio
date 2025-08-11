<?php

namespace jbrowneuk;

/**
 * An action that shows a generic error page
 */
class Error
{
    public function __construct(private readonly IRenderer $renderer) {}

    public function __invoke()
    {
        $this->renderer->setPageId('error');
        $this->renderer->displayPage('error');
    }
}
