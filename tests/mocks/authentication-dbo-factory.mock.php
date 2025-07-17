<?php

namespace jbrowneuk;

function authentication_dbo_factory(\PDO $pdo): IAuthenticationDBO
{
    $mock = \Mockery::mock(IAuthenticationDBO::class);
    $mock->shouldReceive('verifyUser')->with('valid', 'valid')->andReturn(true);
    $mock->shouldReceive('verifyUser')->andReturn(false);
    return $mock;
}
