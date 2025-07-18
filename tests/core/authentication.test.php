<?php

namespace jbrowneuk;

require_once 'src/interfaces/iauthenticationdbo.php';

require_once 'src/database/authentication.dbo.php';

require_once 'tests/mocks/authentication-dbo-factory.mock.php';

require_once 'src/core/authentication.php';

describe('Authentication controller', function () {
    beforeEach(function () {
        $this->authentication = new Authentication(\Mockery::mock(\PDO::class));
    });

    afterEach(function () {
        if (session_id()) session_destroy();
    });

    describe('Login', function() {
        it('should not allow login of invalid user', function () {
            $username = 'invalid';
            $password = 'invalid';

            $result = $this->authentication->login($username, $password);

            expect($result)->toBeFalse();
            expect(isset($_SESSION[Authentication::LOGGED_IN_KEY]))->toBeFalse();
        });

        it('should allow login of valid user and regenerate session', function () {
            session_start();
            $originalId = session_id();

            $username = 'valid';
            $password = 'valid';

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
