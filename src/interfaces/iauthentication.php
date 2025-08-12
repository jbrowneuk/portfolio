<?php

namespace jbrowneuk;

interface IAuthentication {
    /**
     * Gets whether a user is authenticated
     */
    public function isAuthenticated(): bool;

    /**
     * Attempts to log in a user identified with the username and password. Returns success state.
     *
     * @param string $username the user's name
     * @param string $password the user's password
     *
     * @return bool `true` if successful (i.e. the user exists), `false` if not
     */
    public function login(string $username, #[\SensitiveParameter] string $password): bool;

    /**
     * Logs out the current user and clears the session
     */
    public function logout();
}
