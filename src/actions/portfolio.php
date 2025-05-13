<?php

namespace jbrowneuk;

class Portfolio implements Action
{
    public function render($pdo, $renderer, $pageParams)
    {
        $renderer->setPageId('portfolio');
        $renderer->displayPage('top-page');
    }
}
