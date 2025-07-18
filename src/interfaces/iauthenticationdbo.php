<?php

namespace jbrowneuk;

interface IAuthenticationDBO
{
    /**
     * Verifies whether a user (identified by username, password) exists in the database
     *
     * @param string $username the user's name
     * @param string $password the user's password
     *
     * @return boolean whether the user exists in the database
     */
    public function verifyUser(string $username, #[\SensitiveParameter] string $password): bool;
}
