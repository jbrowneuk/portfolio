<?php
namespace jbrowneuk;

require_once 'src/core/page.php';
require_once 'src/core/renderer.php';

require_once 'src/actions/projects.php';

beforeEach(function () {
    $this->mockPdo = $this->createMock(\PDO::class);
    $this->mockRenderer = $this->createMock(PortfolioRenderer::class);

    $this->action = new Projects();
});

it('should set page id', function () {
    $this->mockRenderer->expects($this->once())->method('setPageId')->with('projects');
    $this->action->render($this->mockPdo, $this->mockRenderer);
});

it('should display page on template', function () {
    $this
        ->mockRenderer
        ->expects($this->atLeastOnce())
        ->method('displayPage')
        ->with('projects');

    $this->action->render($this->mockPdo, $this->mockRenderer);
});