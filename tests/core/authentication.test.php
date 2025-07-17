<?php

namespace jbrowneuk;

require_once 'src/database/authentication.dbo.php';

require_once 'src/core/authentication.php';

describe('Authentication controller', function () {
    beforeEach(function () {
        $this->mockPdo = \Mockery::mock(\PDO::class);
        $this->authentication = new Authentication($this->mockPdo);
    });

    afterEach(function () {
        if (session_id()) session_destroy();
    });

    describe('Login', function() {
        it('should not allow login of invalid user', function () {
            $username = 'bad-username';
            $password = 'bad-password';

            $mockStatement = \Mockery::mock(\PDOStatement::class);
            $mockStatement->shouldReceive('execute')->with(['username' => $username]);
            $mockStatement->shouldReceive('fetch')->andReturn(false);

            $this->mockPdo->shouldReceive('prepare')->andReturn($mockStatement);

            $result = $this->authentication->login($username, $password);
            expect($result)->toBeFalse();
            expect(isset($_SESSION[Authentication::LOGGED_IN_KEY]))->toBeFalse();
        });

        it('should allow login of valid user and regenerate session', function () {
            session_start();
            $originalId = session_id();

            $username = 'any-username';

            // This password and hash are taken from the PHP password_hash documentation page
            // and can be found at https://www.php.net/manual/en/function.password-hash.php
            $password = 'rasmuslerdorf';
            $pwHash = '$2y$12$4Umg0rCJwMswRw/l.SwHvuQV01coP0eWmGzd61QH2RvAOMANUBGC.';

            $mockStatement = \Mockery::mock(\PDOStatement::class);
            $mockStatement->shouldReceive('execute')->with(['username' => $username]);
            $mockStatement->shouldReceive('fetch')->andReturn(['user_id' => $username, 'hash' => $pwHash]);
            $this->mockPdo->shouldReceive('prepare')->andReturn($mockStatement);

            $result = $this->authentication->login($username, $password);

            expect($result)->toBeTrue();
            expect(session_id())->not()->toBe($originalId);
            expect(isset($_SESSION[Authentication::LOGGED_IN_KEY]))->toBeTrue();
        });
    });

    describe('Logout', function () {
        it('should clear session if logout is called', function () {
            session_start();
            expect(session_id())->toBeTruthy();

            $this->authentication->logout();

            expect(session_id())->toBeFalsy();
        });
    });

    describe('When not authenticated', function () {
        it('should return false for isAuthenticated', function () {
            $result = $this->authentication->isAuthenticated();
            expect($result)->toBeFalse();
        });
    });

    describe('When authenticated', function () {
        beforeEach(function () {
            session_start();
            $_SESSION[Authentication::LOGGED_IN_KEY] = true;
        });

        it('should return true for isAuthenticated', function () {
            $result = $this->authentication->isAuthenticated();
            expect($result)->toBeTrue();
        });
    });
});
