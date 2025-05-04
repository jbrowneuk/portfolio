<?php

namespace jbrowneuk;

interface Page
{
    public function render(\PDO $pdo, PortfolioRenderer $renderer, array $pageParams);
}
