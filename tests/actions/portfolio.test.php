<?php

namespace jbrowneuk;

require_once 'src/interfaces/irenderer.php';

require_once 'src/actions/portfolio.php';

describe('Portfolio Top Page Action', function () {
    beforeEach(function () {
        $this->mockRenderer = \Mockery::spy(IRenderer::class);

        $this->action = new Portfolio($this->mockRenderer);
    });

    afterEach(function () {
        \Mockery::close();
    });

    it('should set page id', function () {
        $this->mockRenderer->shouldReceive('setPageId')->once()->with('portfolio');
        ($this->action)();
    });

    it('should display page on template', function () {
        $this->mockRenderer->shouldReceive('displayPage')->once()->with('top-page');
        ($this->action)();
    });
});
