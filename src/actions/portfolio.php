<?php

namespace jbrowneuk;

/**
 * An action that renders the top page of the portfolio
 */
class Portfolio implements IAction
{
    public function render(\PDO $pdo, PortfolioRenderer $renderer, array $pageParams): void
    {
        $renderer->setPageId('portfolio');
        $renderer->displayPage('top-page');
    }
}
