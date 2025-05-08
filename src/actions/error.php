<?php

namespace jbrowneuk;

class Error implements Page
{
  public function render($pdo, $renderer, $pageParams)
  {
    $renderer->setPageId('error');
    $renderer->displayPage('error');
  }
}
