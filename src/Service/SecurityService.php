<?php

namespace App\Service;

use App\App;
use App\User\AdminUser;
use App\User\BaseUser;
use App\User\DefaultUser;
use App\Util\Database;

class SecurityService
{
    private $connection;

    public function __construct(Database $database)
    {
        $this->connection = $database->getConnection();
    }

    public function isEmailInDatabase($email): bool
    {
        $statement = $this->connection->prepare('SELECT email FROM users WHERE email = ?');
        $statement->bind_param('s', $email);
        $statement->execute();
        $result = $statement->get_result();
        $statement->close();

        return $result->num_rows > 0;
    }

    public function login(string $email, string $password): ?BaseUser
    {
        if (self::getUser() != null) return self::getUser();

        $hash = crypt($password, $email);
        $statement = $this->connection->prepare('SELECT * FROM users WHERE email = ? AND password = ?');
        $statement->bind_param('ss', $email, $hash);
        $statement->execute();
        $result = $statement->get_result();
        if (!$result) return null;
        if ($result->num_rows < 1) return null;
        $row = $result->fetch_assoc();

        if ($row['role'] == 'ADMIN') {
            $user = new AdminUser($row['id'], $row['email'], $row['vorname'], $row['nachname']);
        } else {
            $user = new DefaultUser($row['id'], $row['email'], $row['vorname'], $row['nachname']);
        }

        $statement->close();

        $_SESSION['user'] = serialize($user);
        return $user;
    }

    public function logout(): void
    {
        session_destroy();
    }

    public function getUser(): ?BaseUser
    {
        $user = unserialize($_SESSION['user']);
        return !$user ? null : $user;
    }
}