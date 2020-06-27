<?php

namespace App\Service;

use App\App;
use App\User\BaseUser;
use App\Util\Database;
use mysqli;

class UserService
{
    private $connection;

    public function __construct(Database $database)
    {
        $this->connection = $database->getConnection();
    }

    public function deleteUser(int $id): void
    {
        $statement = $this->connection->prepare('DELETE FROM users WHERE id = ?');
        $statement->bind_param('i', $id);
        $statement->execute();
    }

    public function addUser(BaseUser $user, string $password): void
    {
        $hash = crypt($password, $user->getEmail());
        $role = ($user->getAccessLevel() == 2 ? 'ADMIN' : null);
        $statement = $this->connection->prepare('INSERT INTO users (vorname, nachname, email, role, password) VALUES (?, ?, ?, ?, ?)');
        $statement->bind_param('sssss', $user->getVorname(), $user->getNachname(), $user->getEmail(), $role, $hash);
        $statement->execute();
    }
}