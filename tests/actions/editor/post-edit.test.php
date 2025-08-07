<?php

namespace jbrowneuk;

require_once 'src/interfaces/ieditorroute.php';

require_once 'src/core/renderer.php';

require_once 'src/actions/editor/post-edit.php';

describe('Editor: Post edit sub-action', function () {
    beforeEach(function () {
        $this->postsDBO = \Mockery::mock(IPostsDBO::class);
        $this->portfolioRenderer = \Mockery::mock(PortfolioRenderer::class);

        $this->postEditor = new PostEditor();
    });

    afterEach(function () {
        \Mockery::close();
    });

    describe('on GET', function () {
        beforeEach(function () {
            // mock request method
            $_SERVER['REQUEST_METHOD'] = 'GET';
        });

        it('should redirect to \'\' when no mode specified', function () {
            $this->portfolioRenderer->shouldReceive('redirectTo')->once()->with('');

            $this->postEditor->render($this->postsDBO, $this->portfolioRenderer, []);
        });

        it('should assign null post and display editor in create mode', function () {
            $this->portfolioRenderer->shouldReceive('assign')->once()->with('post', null);
            $this->portfolioRenderer->shouldReceive('displayPage')->once()->with('post-editor');

            $this->postEditor->render($this->postsDBO, $this->portfolioRenderer, [PostEditor::ROUTE_MODE_PARAM, PostEditor::MODE_CREATE]);
        });

        it('should assign valid post and display editor in edit mode for existing post', function () {
            $this->postsDBO->shouldReceive('getPost')->andReturn(MOCK_POST);

            $this->portfolioRenderer->shouldReceive('assign')->once()->with('post', MOCK_POST);
            $this->portfolioRenderer->shouldReceive('displayPage')->once()->with('post-editor');

            $this->postEditor->render($this->postsDBO, $this->portfolioRenderer, [PostEditor::ROUTE_MODE_PARAM, PostEditor::MODE_EDIT, MOCK_POST['post_id']]);
        });

        it('should assign null post and display editor in edit mode for non-existing post', function () {
            $this->postsDBO->shouldReceive('getPost')->andReturn(null);

            $this->portfolioRenderer->shouldReceive('assign')->once()->with('post', null);
            $this->portfolioRenderer->shouldReceive('displayPage')->once()->with('post-editor');

            $this->postEditor->render($this->postsDBO, $this->portfolioRenderer, [PostEditor::ROUTE_MODE_PARAM, PostEditor::MODE_EDIT, 'mocked-nonexistent']);
        });
    });

    describe('on POST', function () {
        beforeEach(function () {
            // mock request method
            $_SERVER['REQUEST_METHOD'] = 'POST';
        });

        it('should generate title error if post title not defined', function () {
            $_POST = [];

            $this->portfolioRenderer->shouldReceive('assign')->atLeast()->times(1); // for other things
            $this->portfolioRenderer->shouldReceive('assign')->with('titleError', true);
            $this->portfolioRenderer->shouldReceive('displayPage')->once()->with('post-editor');

            $this->postEditor->render($this->postsDBO, $this->portfolioRenderer, []);
        });
    });
});
