<?php

namespace jbrowneuk;

/**
 * Encapsulates a page action, defined by a unique route
 */
interface IAction
{
    /**
     * Renders the page
     *
     * @param \PDO $pdo an initialised PDO connection object
     * @param PortfolioRenderer $renderer the page renderer object
     * @param array $pageParams the parameters passed to the page through the URL
     */
    public function render(\PDO $pdo, PortfolioRenderer $renderer, array $pageParams): void;
}
