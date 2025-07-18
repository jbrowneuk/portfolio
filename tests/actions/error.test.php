<?php

namespace jbrowneuk;

require_once 'src/interfaces/iaction.php';

require_once 'src/core/renderer.php';

require_once 'src/actions/error.php';

describe('Error Action', function () {
    beforeEach(function () {
        $this->mockPdo = $this->createMock(\PDO::class);
        $this->mockRenderer = $this->createMock(PortfolioRenderer::class);

        $this->action = new Error();
    });

    it('should set page id', function () {
        $this->mockRenderer->expects($this->once())->method('setPageId')->with('error');
        $this->action->render($this->mockPdo, $this->mockRenderer, []);
    });

    it('should display page on template', function () {
        $this
            ->mockRenderer
            ->expects($this->atLeastOnce())
            ->method('displayPage')
            ->with('error');

        $this->action->render($this->mockPdo, $this->mockRenderer, []);
    });
});
