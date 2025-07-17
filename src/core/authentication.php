<?php

namespace jbrowneuk;

/**
 * Controls user authentication on the site
 */
class Authentication
{
    // Public to allow testing
    public const string LOGGED_IN_KEY = 'logged-in';

    private AuthenticationDBO $dbo;

    /**
     * Constructs a new instance of the authentication controller
     *
     * @param \PDO $pdo an active PDO object
     */
    public function __construct(private \PDO $pdo)
    {
        $this->dbo = new AuthenticationDBO($pdo);
    }

    /**
     * Gets whether a user is authenticated
     */
    public function isAuthenticated(): bool
    {
        $this->initSession();
        return isset($_SESSION[self::LOGGED_IN_KEY]);
    }

    /**
     * Attempts to log in a user identified with the username and password. Returns success state.
     *
     * @param string $username the user's name
     * @param string $password the user's password
     *
     * @return bool `true` if successful (i.e. the user exists), `false` if not
     */
    public function login(string $username, #[\SensitiveParameter] string $password): bool
    {
        $this->initSession();

        if ($this->dbo->verifyUser($username, $password)) {
            session_regenerate_id();
            $_SESSION[self::LOGGED_IN_KEY] = true;
            return true;
        }

        return false;
    }

    /**
     * Logs out the current user and clears the session
     */
    public function logout()
    {
        $this->initSession();
        $_SESSION = [];
        session_destroy();
    }

    /**
     * Convenience method to initialise the session if it does not exist
     */
    private function initSession()
    {
        $sid = session_id();
        if ($sid !== false && $sid !== '') {
            return;
        }

        session_start();
    }
}
