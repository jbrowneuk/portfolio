<?php

namespace jbrowneuk;

/**
 * A database object containing helpers for user authentication
 */
class AuthenticationDBO
{
    /**
     * Constructs an instance of the Authentication Database Object
     */
    public function __construct(private readonly \PDO $pdo) {}

    /**
     * Verifies whether a user (identified by username, password) exists in the database
     *
     * @param string $username the user's name
     * @param string $password the user's password
     *
     * @return boolean whether the user exists in the database
     */
    public function verifyUser(string $username, #[\SensitiveParameter] string $password): bool
    {
        $statement = $this->pdo->prepare('SELECT * FROM users WHERE user_id = :username LIMIT 1');
        $statement->execute(['username' => $username]);
        $row = $statement->fetch(\PDO::FETCH_ASSOC);
        if ($row === false) {
            return false;
        }

        $return = password_verify($password, $row['hash']);
        return $return;
    }
}
