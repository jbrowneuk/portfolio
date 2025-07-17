<?php

namespace jbrowneuk;

require_once 'src/interfaces/iaction.php';

require_once 'src/core/authentication.php';
require_once 'src/core/renderer.php';

require_once 'src/database/authentication.dbo.php';

require_once 'src/actions/auth.php';

describe('Authentication page', function () {
    beforeEach(function () {
        session_start();

        $this->mockPdo = \Mockery::mock(\PDO::class);
        $this->mockRenderer = \Mockery::mock(PortfolioRenderer::class);
        $this->mockRenderer->shouldReceive('setPageId')->zeroOrMoreTimes();
        $this->mockRenderer->shouldReceive('displayPage')->zeroOrMoreTimes();

        $this->action = new Auth();

        // Mock server superglobal
        $_SERVER['REQUEST_METHOD'] = 'GET';
    });

    afterEach(function () {
        if (session_id()) session_destroy();
    });

    describe('When authenticated', function () {
        beforeEach(function () {
            $_SESSION[Authentication::LOGGED_IN_KEY] = true;
        });

        describe('Login page', function () {
            it('should redirect to editor page', function () {
                $this->mockRenderer->shouldReceive('redirectTo')->with('editor');
                $this->action->render($this->mockPdo, $this->mockRenderer, []);
            });
        });

        describe('logout page', function () {
            $params = ['logout'];

            it('should redirect to main page', function () use ($params) {
                $this->mockRenderer->shouldReceive('redirectTo')->with('');
                $this->action->render($this->mockPdo, $this->mockRenderer, $params);
            });

            it('should clear session', function () use ($params) {
                $this->mockRenderer->shouldReceive('redirectTo')->once();
                expect(isset($_SESSION[Authentication::LOGGED_IN_KEY]))->toBeTrue();

                $this->action->render($this->mockPdo, $this->mockRenderer, $params);

                expect(isset($_SESSION[Authentication::LOGGED_IN_KEY]))->toBeFalse();
            });
        });
    });

    describe('When not authenticated', function () {
        describe('Login page', function () {
            it('should set page id', function () {
                $this->mockRenderer->shouldReceive('setPageId')->with('auth');
                $this->action->render($this->mockPdo, $this->mockRenderer, []);
            });

            it('should display login page template', function () {
                $this->mockRenderer->shouldReceive('displayPage')->with('login');
                $this->action->render($this->mockPdo, $this->mockRenderer, []);
            });

            it('should attempt to login user and redirect on form submission with valid user', function () {
                $username = 'any-username';

                // This password and hash are taken from the PHP password_hash documentation page
                // and can be found at https://www.php.net/manual/en/function.password-hash.php
                $password = 'rasmuslerdorf';
                $pwHash = '$2y$12$4Umg0rCJwMswRw/l.SwHvuQV01coP0eWmGzd61QH2RvAOMANUBGC.';

                $_SERVER['REQUEST_METHOD'] = 'POST';
                $_POST['username'] = $username;
                $_POST['password'] = $password;

                $mockStatement = \Mockery::mock(\PDOStatement::class);
                $mockStatement->shouldReceive('execute')->with(['username' => $username]);
                $mockStatement->shouldReceive('fetch')->andReturn(['user_id' => $username, 'hash' => $pwHash]);
                $this->mockPdo->shouldReceive('prepare')->andReturn($mockStatement);

                $this->mockRenderer->shouldReceive('redirectTo')->with('editor');
                $this->action->render($this->mockPdo, $this->mockRenderer, []);
            });

            it('should show login error on form submission with invalid user', function () {
                $_SERVER['REQUEST_METHOD'] = 'POST';
                $_POST['username'] = 'any';
                $_POST['password'] = 'any';

                $mockStatement = \Mockery::mock(\PDOStatement::class);
                $mockStatement->shouldReceive('execute');
                $mockStatement->shouldReceive('fetch')->andReturn(false);
                $this->mockPdo->shouldReceive('prepare')->andReturn($mockStatement);

                $this->mockRenderer->shouldNotReceive('redirectTo');
                $this->mockRenderer->shouldReceive('displayPage')->with('login');
                $this->mockRenderer->shouldReceive('assign')->with('loginError', true);

                $this->action->render($this->mockPdo, $this->mockRenderer, []);
            });
        });

        describe('logout page', function () {
            $params = ['logout'];

            it('should redirect to main page', function () use ($params) {
                $this->mockRenderer->shouldReceive('redirectTo')->with('');
                $this->action->render($this->mockPdo, $this->mockRenderer, $params);
            });
        });
    });
});
