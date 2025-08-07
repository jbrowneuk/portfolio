<?php

namespace jbrowneuk;

require_once 'src/interfaces/ieditorroute.php';
require_once 'src/interfaces/iaction.php';

require_once 'src/core/authentication.php';
require_once 'src/core/renderer.php';

require_once 'src/database/authentication.dbo.php';

require_once 'src/actions/editor.php';

const POST_PAGE = 'post';

class TestableEditor extends Editor {
    public function setRoutes(array $routes): void {
        $this->editorRoutes = $routes;
    }
}

describe('Editor Action', function () {
    beforeEach(function () {
        $this->mockPdo = $this->createMock(\PDO::class);
        $this->mockRenderer = $this->createMock(PortfolioRenderer::class);

        $this->action = new TestableEditor();
    });

    afterEach(function () {
        \Mockery::close();
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

        // [TODO] update when default page action is set
        it('should display page on template', function () {
            $this
                ->mockRenderer
                ->expects($this->atLeastOnce())
                ->method('displayPage')
                ->with('editor');

            $this->action->render($this->mockPdo, $this->mockRenderer, []);
        })->todo('update when default page action is set');

        describe('post editor route', function () {
            it('should load post editor', function () {
                // A bit messy, but can't get this to work reliably with a Mock object
                class MockPostPage {
                    public static $hasRendered = false;

                    function render() {
                        self::$hasRendered = true;
                    }
                }

                MockPostPage::$hasRendered = false;
                $this->action->setRoutes([POST_PAGE => MockPostPage::class]);

                $this->action->render($this->mockPdo, $this->mockRenderer, [POST_PAGE]);

                expect(MockPostPage::$hasRendered)->toBeTrue();
            });
        });
    });

    describe('When not authenticated', function () {
        it('should redirect to auth action', function () {
            $this->mockRenderer->expects($this->once())->method('redirectTo')->with('auth');
            $this->action->render($this->mockPdo, $this->mockRenderer, []);
        });
    });
});
