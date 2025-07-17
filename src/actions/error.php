<?php

namespace jbrowneuk;

/**
 * An action that shows a generic error page
 */
class Error implements IAction
{
  public function render(\PDO $pdo, PortfolioRenderer $renderer, array $pageParams): void
  {
    $renderer->setPageId('error');
    $renderer->displayPage('error');
  }
}
