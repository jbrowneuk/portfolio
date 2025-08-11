<?php

namespace jbrowneuk;

require_once 'src/interfaces/iauthentication.php';
require_once 'src/interfaces/irenderer.php';

require_once 'src/actions/auth.php';

describe('Authentication page', function () {
    beforeEach(function () {
        $this->authenticated = true;

        $this->mockAuth = \Mockery::spy(IAuthentication::class);
        $this->mockAuth->shouldReceive('isAuthenticated')->andReturnUsing(fn() => $this->authenticated);

        $this->mockRenderer = \Mockery::spy(IRenderer::class);

        $this->action = new Auth($this->mockAuth, $this->mockRenderer);

        // Mock server superglobal
        $_SERVER['REQUEST_METHOD'] = 'GET';
    });

    afterEach(function () {
        \Mockery::close();
    });

    describe('When authenticated', function () {
        beforeEach(function () {
            $this->authenticated = true;
        });

        describe('Login page', function () {
            it('should redirect to editor page', function () {
                ($this->action)([]);
                $this->mockRenderer->shouldHaveReceived('redirectTo')->with('editor');
            });
        });

        describe('logout page', function () {
            $params = ['logout'];

            it('should redirect to main page', function () use ($params) {
                ($this->action)($params);
                $this->mockRenderer->shouldHaveReceived('redirectTo')->with('');
            });

            it('should call logout on auth object', function () use ($params) {
                ($this->action)($params);
                $this->mockAuth->shouldHaveReceived('logout')->once();
            });
        });
    });

    describe('When not authenticated', function () {
        beforeEach(function () {
            $this->authenticated = false;
        });

        describe('Login page', function () {
            it('should set page id', function () {
                ($this->action)([]);
                $this->mockRenderer->shouldHaveReceived('setPageId')->with('auth');
            });

            it('should display login page template', function () {
                ($this->action)([]);
                $this->mockRenderer->shouldHaveReceived('displayPage')->with('login');
            });

            it('should attempt to login user and redirect on form submission with valid user', function () {
                $_SERVER['REQUEST_METHOD'] = 'POST';
                $_POST['username'] = 'valid';
                $_POST['password'] = 'valid';

                $this->mockAuth->shouldReceive('login')->andReturnUsing(function () {
                    $this->authenticated = true;
                    return true;
                });

                ($this->action)([]);

                $this->mockRenderer->shouldHaveReceived('redirectTo')->with('editor');
            });

            it('should show login error on form submission with invalid user', function () {
                $_SERVER['REQUEST_METHOD'] = 'POST';
                $_POST['username'] = 'invalid';
                $_POST['password'] = 'invalid';

                $this->mockAuth->shouldReceive('login')->andReturn(false);

                ($this->action)([]);

                $this->mockRenderer->shouldNotHaveReceived('redirectTo');
                $this->mockRenderer->shouldHaveReceived('displayPage')->with('login');
                $this->mockRenderer->shouldHaveReceived('assign')->with('loginError', true);
            });
        });

        describe('logout page', function () {
            $params = ['logout'];

            it('should redirect to main page', function () use ($params) {
                ($this->action)($params);
                $this->mockRenderer->shouldHaveReceived('redirectTo')->with('');
            });
        });
    });
});
