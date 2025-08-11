<?php

namespace jbrowneuk;

/**
 * An action that renders the top page of the portfolio
 */
class Portfolio
{
    public function __construct(private readonly IRenderer $renderer) {}

    public function __invoke()
    {
        $this->renderer->setPageId('portfolio');
        $this->renderer->displayPage('top-page');
    }
}
