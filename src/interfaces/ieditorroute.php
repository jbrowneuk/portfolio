<?php

namespace jbrowneuk;

/**
 * Encapsulates an editor page action, defined by a route parameter
 */
interface IEditorRoute {
    public function render(IPostsDBO $dbo, PortfolioRenderer $renderer, array $pageParams): void;
}
