<?php

namespace jbrowneuk;

interface Action
{
    public function render(\PDO $pdo, PortfolioRenderer $renderer, array $pageParams);
}
