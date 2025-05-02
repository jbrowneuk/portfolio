<?php
namespace jbrowneuk;

interface Page {
    public function render(\PDO $pdo, PageRenderer $renderer, array $pageParams);
}