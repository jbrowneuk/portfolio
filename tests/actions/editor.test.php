<?php

namespace jbrowneuk;

require_once 'src/core/action.php';
require_once 'src/core/authentication.php';
require_once 'src/core/renderer.php';

require_once 'src/database/authentication.dbo.php';

require_once 'src/actions/editor.php';

describe('Editor Action', function () {
    beforeEach(function () {
        $this->mockPdo = $this->createMock(\PDO::class);
        $this->mockRenderer = $this->createMock(PortfolioRenderer::class);

        $this->action = new Editor();
    });

    describe('When authenticated', function () {
        beforeEach(function () {
            session_start();
            $_SESSION[Authentication::LOGGED_IN_KEY] = true;
        });

        afterEach(function () {
            session_destroy();
        });

        it('should set page id', function () {
            $this->mockRenderer->expects($this->once())->method('setPageId')->with('admin');
            $this->action->render($this->mockPdo, $this->mockRenderer, []);
        });

        it('should display page on template', function () {
            $this
                ->mockRenderer
                ->expects($this->atLeastOnce())
                ->method('displayPage')
                ->with('editor');

            $this->action->render($this->mockPdo, $this->mockRenderer, []);
        });
    });

    describe('When not authenticated', function () {
        it('should redirect to auth action', function () {
            $this->mockRenderer->expects($this->once())->method('redirectTo')->with('auth');
            $this->action->render($this->mockPdo, $this->mockRenderer, []);
        });
    });
});
