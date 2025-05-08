<?php

namespace jbrowneuk;

require_once 'src/core/page.php';
require_once 'src/core/renderer.php';

require_once 'src/actions/portfolio.php';

beforeEach(function () {
    $this->mockPdo = $this->createMock(\PDO::class);
    $this->mockRenderer = $this->createMock(PortfolioRenderer::class);

    $this->action = new Portfolio();
});

it('should set page id', function () {
    $this->mockRenderer->expects($this->once())->method('setPageId')->with('portfolio');
    $this->action->render($this->mockPdo, $this->mockRenderer, []);
});

it('should display page on template', function () {
    $this
        ->mockRenderer
        ->expects($this->atLeastOnce())
        ->method('displayPage')
        ->with('top-page');

    $this->action->render($this->mockPdo, $this->mockRenderer, []);
});
