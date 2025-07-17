<?php

namespace jbrowneuk;

/**
 * A database object containing helpers for user authentication
 */
class AuthenticationDBO implements IAuthenticationDBO
{
    /**
     * Constructs an instance of the Authentication Database Object
     */
    public function __construct(private readonly \PDO $pdo) {}

    public function verifyUser(string $username, #[\SensitiveParameter] string $password): bool
    {
        $statement = $this->pdo->prepare('SELECT * FROM users WHERE user_id = :username LIMIT 1');
        $statement->execute(['username' => $username]);
        $row = $statement->fetch(\PDO::FETCH_ASSOC);
        if ($row === false) {
            return false;
        }

        return password_verify($password, $row['hash']);
    }
}
