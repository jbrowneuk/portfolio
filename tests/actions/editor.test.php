<?php

namespace jbrowneuk;

require_once 'src/interfaces/iauthentication.php';
require_once 'src/interfaces/irenderer.php';

require_once 'src/actions/editor.php';

describe('Editor Action', function () {
    beforeEach(function () {
        $this->mockAuth = \Mockery::mock(IAuthentication::class);
        $this->mockRenderer = $this->createMock(IRenderer::class);

        $this->action = new Editor($this->mockAuth, $this->mockRenderer);
    });

    afterEach(function () {
        \Mockery::close();
    });

    describe('When authenticated', function () {
        beforeEach(function () {
            $this->mockAuth->shouldReceive('isAuthenticated')->andReturn(true);
        });

        it('should set page id', function () {
            $this->mockRenderer->expects($this->once())->method('setPageId')->with('admin');
            ($this->action)([]);
        });

        it('should display page on template', function () {
            $this
                ->mockRenderer
                ->expects($this->atLeastOnce())
                ->method('displayPage')
                ->with('editor');

            ($this->action)([]);
        });
    });

    describe('When not authenticated', function () {
        beforeEach(function () {
            $this->mockAuth->shouldReceive('isAuthenticated')->andReturn(false);
        });

        it('should redirect to auth action', function () {
            $this->mockRenderer->expects($this->once())->method('redirectTo')->with('auth');
            ($this->action)([]);
        });
    });
});
