<?php

namespace jbrowneuk;

class Error implements Action
{
  public function render($pdo, $renderer, $pageParams)
  {
    $renderer->setPageId('error');
    $renderer->displayPage('error');
  }
}
