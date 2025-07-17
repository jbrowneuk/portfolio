<?php

namespace jbrowneuk;

require_once 'src/database/authentication.dbo.php';

describe('Authentication Database Object', function () {
    beforeEach(function () {
        $this->mockPdo = \Mockery::mock(\PDO::class);
        $this->authentication = new AuthenticationDBO($this->mockPdo);
    });

    describe('verifyUser', function () {
        it('should return false for invalid user', function () {
            $username = 'bad-username';
            $password = 'bad-password';

            $mockStatement = \Mockery::mock(\PDOStatement::class);
            $mockStatement->shouldReceive('execute')->with(['username' => $username]);
            $mockStatement->shouldReceive('fetch')->andReturn(false);

            $this->mockPdo->shouldReceive('prepare')->andReturn($mockStatement);

            $result = $this->authentication->verifyUser($username, $password);
            expect($result)->toBeFalse();
        });

        it('should allow login of valid user and regenerate session', function () {
            $username = 'any-username';

            // This password and hash are taken from the PHP password_hash documentation page
            // and can be found at https://www.php.net/manual/en/function.password-hash.php
            $password = 'rasmuslerdorf';
            $pwHash = '$2y$12$4Umg0rCJwMswRw/l.SwHvuQV01coP0eWmGzd61QH2RvAOMANUBGC.';

            $mockStatement = \Mockery::mock(\PDOStatement::class);
            $mockStatement->shouldReceive('execute')->with(['username' => $username]);
            $mockStatement->shouldReceive('fetch')->andReturn(['user_id' => $username, 'hash' => $pwHash]);
            $this->mockPdo->shouldReceive('prepare')->andReturn($mockStatement);

            $result = $this->authentication->verifyUser($username, $password);

            expect($result)->toBeTrue();
        });
    });
});
