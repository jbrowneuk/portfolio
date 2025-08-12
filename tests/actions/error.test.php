<?php

namespace jbrowneuk;

require_once 'src/interfaces/irenderer.php';

require_once 'src/actions/error.php';

describe('Error Action', function () {
    beforeEach(function () {
        $this->mockRenderer = \Mockery::spy(IRenderer::class);

        $this->action = new Error($this->mockRenderer);
    });

    afterEach(function () {
        \Mockery::close();
    });

    it('should set page id', function () {
        ($this->action)();
        $this->mockRenderer->shouldHaveReceived('setPageId')->once()->with('error');
    });

    it('should display page on template', function () {
        ($this->action)();
        $this->mockRenderer->shouldHaveReceived('displayPage')->once()->with('error');
    });
});
